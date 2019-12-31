<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\ChargeResponse;

use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use App\Infrastructure\Payment\Workflow\PaymentWorkflowContextPreparationTrait;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class FailedChargeResponseStrategy implements ChargeResponseStrategyInterface
{
    use PaymentWorkflowContextPreparationTrait;

    /** @var Registry */
    private $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function updatePaymentByResponse(PaymentInterface $payment, array $responseData): void
    {
        $workflow = $this->workflowRegistry->get($payment, Payment::GRAPH);
        $context = $this->preparePaymentDetailsContext($responseData);

        try {
            $workflow->apply($payment, Payment::TRANSITION_FAIL, $context);
        } catch (LogicException $e) {
            throw new PaymentLogicException("Payment with state {$payment->getState()} can't be failed");
        }
    }

    public function validate(string $status): bool
    {
        if (GetHumanStatus::STATUS_FAILED !== $status) {
            return false;
        }

        return true;
    }
}