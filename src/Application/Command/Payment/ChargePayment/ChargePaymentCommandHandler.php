<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\ChargePayment;

use App\Domain\Payment\GatewayConfig;
use Exception;
use Payum\Core\Exception\Http\HttpException as PayumHttpException;
use App\Application\Service\Payment\Strategy\ChargeResponse\ChargeResponseContext;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use App\Infrastructure\Payment\Strategy\ChargeRequestOptions\ChargeRequestOptionsContext;
use Payum\Core\Payum;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class ChargePaymentCommandHandler implements MessageHandlerInterface
{
    /** @var Payum */
    private $payum;

    /** @var Registry */
    private $workflowRegistry;

    /** @var ChargeResponseContext */
    private $chargeResponseContext;

    /** @var ChargeRequestOptionsContext */
    private $requestOptionsContext;

    public function __construct(
        Payum $payum,
        Registry $workflowRegistry,
        ChargeResponseContext $chargeResponseContext,
        ChargeRequestOptionsContext $requestOptionsContext
    ) {
        $this->payum = $payum;
        $this->workflowRegistry = $workflowRegistry;
        $this->chargeResponseContext = $chargeResponseContext;
        $this->requestOptionsContext = $requestOptionsContext;
    }

    public function __invoke(ChargePaymentCommand $command): PaymentInterface
    {
        $payment = $command->getPayment();

        $workflow = $this->workflowRegistry->get($payment, Payment::GRAPH);

        try {
            $workflow->apply($payment, Payment::TRANSITION_PROCESS);
        } catch (LogicException $e) {
            throw new BadRequestHttpException("Payment with state {$payment->getState()} can't be processed");
        }

        $paymentMethod = $payment->getPaymentMethod();

        if (null === $paymentMethod) {
            $gatewayName = GatewayConfig::GATEWAY_NAME_OFFLINE;
        } else {
            $gatewayConfig = $paymentMethod->getGatewayConfig();
            $gatewayName = $gatewayConfig->getGatewayName();
        }

        $options = $this->requestOptionsContext
            ->getPreparedRequestOptions($gatewayName, $payment->getAmount(), $command->getOptions() ?? []);

        $gateway = $this->payum->getGateway($gatewayName);

        $captureRequest = new Capture($options);

        try {
            $gateway->execute($captureRequest);
        } catch (PayumHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $captureResponse = $captureRequest->getModel();

        $statusRequest = new GetHumanStatus($captureResponse);
        $gateway->execute($statusRequest);

        $status = (string)$statusRequest->getValue();

        try {
            $this->chargeResponseContext->updatePaymentByResponse($payment, $captureResponse->getArrayCopy(), $status);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $payment;
    }
}