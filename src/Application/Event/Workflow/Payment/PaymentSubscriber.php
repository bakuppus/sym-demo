<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Payment;

use App\Application\Command\Payment\UpdatePayment\UpdatePaymentCommand;
use App\Domain\Payment\Core\PaymentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\TransitionEvent;

final class PaymentSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.payment.transition.complete' => 'updatePayment',
            'workflow.payment.transition.refund' => 'updatePayment',
            'workflow.payment.transition.fail' => 'updatePayment',
        ];
    }

    public function updatePayment(TransitionEvent $transitionEvent): void
    {
        /** @var PaymentInterface $payment */
        $payment = $transitionEvent->getSubject();

        $context = $transitionEvent->getContext();

        if (isset($context['details']) && null !== $context['details'] && true === is_array($context['details'])) {
            $updatePaymentCommand = new UpdatePaymentCommand($payment);
            $updatePaymentCommand->setOrder($payment->getOrder());
            $updatePaymentCommand->setPaymentMethod($payment->getPaymentMethod());
            $updatePaymentCommand->setAmount($payment->getAmount());
            $updatePaymentCommand->setCurrencyCode($payment->getCurrencyCode());
            $updatePaymentCommand->setDetails($context['details']);

            $this->messageBus->dispatch($updatePaymentCommand);
        }
    }
}