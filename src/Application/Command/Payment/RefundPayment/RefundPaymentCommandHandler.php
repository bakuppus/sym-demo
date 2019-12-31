<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\RefundPayment;

use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\GatewayConfig;
use App\Domain\Payment\Payment;
use App\Infrastructure\Payment\Strategy\ChargeRequestOptions\ChargeRequestOptionsContext;
use App\Infrastructure\Payment\Strategy\RefundRequestOptions\RefundRequestOptionsContext;
use App\Infrastructure\Payment\Workflow\PaymentWorkflowContextPreparationTrait;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Refund;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;
use Payum\Core\Exception\Http\HttpException as PayumHttpException;

final class RefundPaymentCommandHandler implements MessageHandlerInterface
{
    use PaymentWorkflowContextPreparationTrait;

    /** @var Payum */
    private $payum;

    /** @var Registry */
    private $workflowRegistry;

    /** @var ChargeRequestOptionsContext */
    private $requestOptionsContext;

    public function __construct(
        Payum $payum,
        Registry $workflowRegistry,
        RefundRequestOptionsContext $requestOptionsContext
    ) {
        $this->payum = $payum;
        $this->workflowRegistry = $workflowRegistry;
        $this->requestOptionsContext = $requestOptionsContext;
    }

    public function __invoke(RefundPaymentCommand $command): PaymentInterface
    {
        $payment = $command->getPayment();

        $workflow = $this->workflowRegistry->get($payment, Payment::GRAPH);

        if (false === $workflow->can($payment, Payment::TRANSITION_REFUND)) {
            throw new BadRequestHttpException("Payment with state {$payment->getState()} can't be refunded");
        }

        $paymentMethod = $payment->getPaymentMethod();

        if (null === $paymentMethod) {
            $gatewayName = GatewayConfig::GATEWAY_NAME_OFFLINE;
        } else {
            $gatewayConfig = $paymentMethod->getGatewayConfig();
            $gatewayName = $gatewayConfig->getGatewayName();
        }

        $gateway = $this->payum->getGateway($gatewayName);

        $options = $this->requestOptionsContext
            ->getPreparedRequestOptions($gatewayName, $payment->getAmount(), $payment->getDetails() ?? []);

        $refundRequest = new Refund($options);

        try {
            $gateway->execute($refundRequest);
        } catch (PayumHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $refundResponse = $refundRequest->getModel();

        $statusRequest = new GetHumanStatus($refundResponse);
        $gateway->execute($statusRequest);

        $status = (string)$statusRequest->getValue();

        $refundResponseArray = $refundResponse->getArrayCopy();

        if (GetHumanStatus::STATUS_REFUNDED !== $status) {
            throw new BadRequestHttpException('Refund failed');
        }

        $workflowContext = $this->preparePaymentDetailsContext($refundResponseArray);

        $workflow->apply($payment, Payment::TRANSITION_REFUND, $workflowContext);

        return $payment;
    }
}