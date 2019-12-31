<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Workflow\Order;

use App\Application\Event\Workflow\Order\MembershipCardSubscriber;
use App\Domain\Club\Club;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;

final class MembershipCardSubscriberTest extends TestCase
{
    public function testOnCreate(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects(self::once())->method('dispatch');

        $membership = new Membership();
        $membership->setClub(new Club());
        $membership->setTotal(100);
        $subject = new MembershipCard();
        $subject->setMembership($membership);
        $subject->setClub($membership->getClub());
        $subject->setPlayer(new Player());

        $event = $this->createMock(Event::class);
        $event->expects(self::once())->method('getSubject')->willReturn($subject);

        $this->assertArrayHasKey(
            'workflow.membership_card.completed.create',
            MembershipCardSubscriber::getSubscribedEvents()
        );
        $this->assertEquals(
            'onCreate',
            MembershipCardSubscriber::getSubscribedEvents()['workflow.membership_card.completed.create']
        );

        $subscriber = new MembershipCardSubscriber($bus);
        $subscriber->onCreate($event);
    }
}
