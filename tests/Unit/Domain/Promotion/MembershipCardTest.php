<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Promotion;

use App\Domain\Accounting\FeeUnit;
use App\Domain\Club\Club;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use PHPUnit\Framework\TestCase;
use DateTime;

final class MembershipCardTest extends TestCase
{
    public function testExpiresAt(): void
    {
        $date = new DateTime();
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getExpiresAt());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setExpiresAt($date));
        $this->assertEquals($date, $membershipCard->getExpiresAt());
    }

    public function testStartsAt(): void
    {
        $date = new DateTime();
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getStartsAt());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setStartsAt($date));
        $this->assertEquals($date, $membershipCard->getStartsAt());
    }

    public function testState(): void
    {
        $membershipCard = new MembershipCard();
        $this->assertEquals(MembershipCard::STATE_INIT, $membershipCard->getState());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setState(MembershipCard::STATE_NEW));
        $this->assertEquals(MembershipCard::STATE_NEW, $membershipCard->getState());
    }

    public function testMembership(): void
    {
        $membership = new Membership();
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getMembership());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setMembership($membership));
        $this->assertEquals($membership, $membershipCard->getMembership());
    }

    public function testDurationType(): void
    {
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getDurationType());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setDurationType(Membership::DURATION_ANNUAL));
        $this->assertEquals(Membership::DURATION_ANNUAL, $membershipCard->getDurationType());
    }

    public function testPlayer(): void
    {
        $player = new Player();
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getPlayer());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setPlayer($player));
        $this->assertEquals($player, $membershipCard->getPlayer());
    }

    public function testClub(): void
    {
        $club = new Club();
        $membershipCard = new MembershipCard();
        $this->assertNull($membershipCard->getClub());
        $this->assertInstanceOf(MembershipCard::class, $membershipCard->setClub($club));
        $this->assertEquals($club, $membershipCard->getClub());
    }
}
