<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Order;

use App\Application\Command\Order\SetOrderPaymentRefund\OrderPaymentRefundCommand;
use App\Application\Command\Order\Workflow\SellOrderCommand;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Payment\Payment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Workflow\Event\Event;

final class PaymentSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function onComplete(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Payment) {
            return;
        }

        $order = $subject->getOrder();

        $command = new SellOrderCommand();
        $command->populate([AbstractNormalizer::OBJECT_TO_POPULATE => $order]);

        $this->messageBus->dispatch($command);
    }

    public function onRefund(Event $event)
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Payment) {
            return;
        }

        $order = $subject->getOrder();

        $command = new OrderPaymentRefundCommand();
        $command->populate([AbstractNormalizer::OBJECT_TO_POPULATE => $order]);

        $this->messageBus->dispatch($command);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.payment.entered.completed' => 'onComplete',
            'workflow.payment.entered.refunded' => 'onRefund',
        ];
    }

    /**
     * {@inheritDoc}
     * @return Payment|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }
}
