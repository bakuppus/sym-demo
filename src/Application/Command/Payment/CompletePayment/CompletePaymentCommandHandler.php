<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\CompletePayment;

use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class CompletePaymentCommandHandler implements MessageHandlerInterface
{
    /** @var Registry */
    private $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function __invoke(CompletePaymentCommand $command): PaymentInterface
    {
        $payment = $command->getPayment();

        $workflow = $this->workflowRegistry->get($payment, Payment::GRAPH);

        $workflow->apply($payment, Payment::TRANSITION_COMPLETE);

        return $payment;
    }
}