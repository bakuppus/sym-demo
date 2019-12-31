<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Workflow\Order;

use App\Application\Event\Workflow\Order\OrderPaymentSubscriber;
use App\Domain\Order\Core\StateResolver\OrderStateResolver;
use App\Domain\Order\Order;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;

final class OrderPaymentSubscriberTest extends TestCase
{
    public function testOnPay(): void
    {
        $subject = new Order();
        $subject->setState(Order::STATE_NEW);
        $subject->setPaymentState(Order::PAYMENT_STATE_AWAITING_PAYMENT);

        $resolver = $this->createMock(OrderStateResolver::class);
        $resolver->expects(self::once())->method('resolve');

        $event = $this->createMock(Event::class);
        $event->expects(self::once())->method('getSubject')->willReturn($subject);

        $this->assertArrayHasKey('workflow.order_payment.entered.paid', OrderPaymentSubscriber::getSubscribedEvents());
        $this->assertEquals(
            'onPay',
            OrderPaymentSubscriber::getSubscribedEvents()['workflow.order_payment.entered.paid']
        );

        $subscriber = new OrderPaymentSubscriber($resolver);
        $subscriber->onPay($event);
    }
}
