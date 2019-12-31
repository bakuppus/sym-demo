<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Workflow\Order;

use App\Application\Assigner\Order\Number\OrderNumberAssigner;
use App\Application\Assigner\Order\Token\HashedOrderTokenAssigner;
use App\Application\Event\Workflow\Order\OrderAssignSubscriber;
use App\Domain\Order\Order;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Workflow\Event\Event;

final class OrderAssignerSubscriberTest extends TestCase
{
    /**
     * {@inheritDoc}
     * @dataProvider dataProvider
     */
    public function testAssignNumber(object $subject): void
    {
        $numberAssigner = $this->createMock(OrderNumberAssigner::class);
        $numberAssigner->expects($this->exactly($subject instanceof Order ? 1 : 0))->method('assignNumber');
        $tokenAssigner = $this->createMock(HashedOrderTokenAssigner::class);
        $tokenAssigner->expects($this->exactly($subject instanceof Order ? 1 : 0))->method('assignTokenIfNotSet');
        $event = $this->createMock(Event::class);
        $event->expects(self::exactly(2))->method('getSubject')->willReturn($subject);

        $this->assertArrayHasKey('workflow.order.completed.create', OrderAssignSubscriber::getSubscribedEvents());
        $this->assertEquals(
            [
                ['assignNumber', 10],
                ['assignToken', 9],
            ],
            OrderAssignSubscriber::getSubscribedEvents()['workflow.order.completed.create']
        );

        $subscriber = new OrderAssignSubscriber($numberAssigner, $tokenAssigner);
        $subscriber->assignNumber($event);
        $subscriber->assignToken($event);
    }

    public function dataProvider(): array
    {
        return [[new Order()], [new stdClass()]];
    }
}
