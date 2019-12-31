<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Payment;

use App\Application\Command\Payment\CompletePayment\CompletePaymentCommand;
use App\Application\Command\Payment\CreatePayment\CreatePaymentCommand;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Order\Order;
use App\Domain\Payment\Core\PaymentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Workflow\Event\Event;

final class OrderSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function onCreate(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Order) {
            return;
        }

        $command = new CreatePaymentCommand();
        $command->setAmount($subject->getTotal());
        $command->setCurrencyCode($subject->getCurrencyCode());
        $command->setOrder($subject);

        $envelope = $this->messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var PaymentInterface $payment */
        $payment = $handledStamp->getResult();

        $subject->addPayment($payment);

        if (0 === $subject->getTotal()) {
            $completePayment = new CompletePaymentCommand();
            $completePayment->setPayment($payment);

            $this->messageBus->dispatch($completePayment);
        }
    }

    /**
     * {@inheritDoc}
     * @return Order|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.order.completed.create' => 'onCreate',
        ];
    }
}
