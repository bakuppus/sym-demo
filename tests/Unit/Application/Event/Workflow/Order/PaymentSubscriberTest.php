<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Workflow\Order;

use App\Application\Event\Workflow\Order\PaymentSubscriber;
use App\Domain\Order\Order;
use App\Domain\Payment\Payment;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;

final class PaymentSubscriberTest extends TestCase
{
    public function testOnComplete(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch');

        $subject = new Payment();
        $subject->setOrder(new Order());

        $event = $this->createMock(Event::class);
        $event->expects(self::once())->method('getSubject')->willReturn($subject);

        $this->assertArrayHasKey('workflow.payment.entered.completed', PaymentSubscriber::getSubscribedEvents());
        $this->assertEquals(
            'onComplete',
            PaymentSubscriber::getSubscribedEvents()['workflow.payment.entered.completed']
        );

        $subscriber = new PaymentSubscriber($messageBus);
        $subscriber->onComplete($event);
    }

    public function testOnCompleteVoid(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::never())->method('dispatch');

        $subject = new stdClass();

        $event = $this->createMock(Event::class);
        $event->expects(self::once())->method('getSubject')->willReturn($subject);

        $this->assertArrayHasKey('workflow.payment.entered.completed', PaymentSubscriber::getSubscribedEvents());
        $this->assertEquals(
            'onComplete',
            PaymentSubscriber::getSubscribedEvents()['workflow.payment.entered.completed']
        );

        $subscriber = new PaymentSubscriber($messageBus);
        $subscriber->onComplete($event);
    }
}
