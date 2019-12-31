<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\ChargeResponse;

use Payum\Core\Exception\Http\HttpException;
use App\Application\Service\Payment\Exception\PaymentCriticalException;
use Exception;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use App\Infrastructure\Payment\Workflow\PaymentWorkflowContextPreparationTrait;
use Payum\Core\Payum;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\Workflow\Registry;

class CapturedChargeResponseStrategy implements ChargeResponseStrategyInterface
{
    use PaymentWorkflowContextPreparationTrait;

    /** @var Registry */
    private $workflowRegistry;

    /** @var Payum */
    private $payum;

    public function __construct(Registry $workflowRegistry, Payum $payum)
    {
        $this->workflowRegistry = $workflowRegistry;
        $this->payum = $payum;
    }

    public function updatePaymentByResponse(PaymentInterface $payment, array $responseData): void
    {
        $workflow = $this->workflowRegistry->get($payment, Payment::GRAPH);
        $context = $this->preparePaymentDetailsContext($responseData);

        try {
            $workflow->apply($payment, Payment::TRANSITION_COMPLETE, $context);
        } catch (Exception $e) {
            $this->cancelApiPayment($payment, $responseData);

            throw $e;
        }
    }

    public function validate(string $status): bool
    {
        if (GetHumanStatus::STATUS_CAPTURED !== $status) {
            return false;
        }

        return true;
    }

    private function cancelApiPayment(PaymentInterface $payment, array $responseData): void
    {
        $paymentMethod = $payment->getPaymentMethod();
        $gatewayConfig = $paymentMethod->getGatewayConfig();
        $gatewayName = $gatewayConfig->getGatewayName();

        $gateway = $this->payum->getGateway($gatewayName);

        $cancelRequest = new Cancel($responseData);

        try {
            $gateway->execute($cancelRequest);
        } catch (HttpException $e) {
            throw new PaymentCriticalException('Payment api cancellation failed');
        }

        return;
    }
}