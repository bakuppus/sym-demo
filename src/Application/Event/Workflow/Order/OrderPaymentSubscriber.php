<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Order;

use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Order\Core\StateResolver\OrderStateResolver;
use App\Domain\Order\Order;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

final class OrderPaymentSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var OrderStateResolver */
    private $orderStateResolver;

    public function __construct(OrderStateResolver $orderStateResolver)
    {
        $this->orderStateResolver = $orderStateResolver;
    }

    public function onPay(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Order) {
            return;
        }

        $this->orderStateResolver->resolve($subject);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.order_payment.entered.paid' => 'onPay',
        ];
    }

    /**
     * {@inheritDoc}
     * @return Order|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }
}
