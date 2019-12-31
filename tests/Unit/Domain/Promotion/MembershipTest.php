<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Promotion;

use App\Domain\Club\Club;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use App\Domain\Promotion\MembershipFee;
use App\Domain\Promotion\Promotion;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

final class MembershipTest extends TestCase
{
    public function testName(): void
    {
        $membership = new Membership();
        $this->assertIsString($membership->getName());
        $this->assertInstanceOf(Membership::class, $membership->setName('some name'));
        $this->assertEquals('some name', $membership->getName());
        $membership = new Membership();
        $this->assertIsString($membership->getName());
        $this->assertInstanceOf(Membership::class, $membership->setName('some name'));
        $this->assertEquals('some name', $membership->getName());
    }

    public function testTotal(): void
    {
        $membership = new Membership();
        $this->assertIsInt($membership->getTotal());
        $this->assertInstanceOf(Membership::class, $membership->setTotal(10000));
        $this->assertEquals(0, $membership->getTotal());

        $membershipFee = new MembershipFee();
        $membershipFee->setPrice(10000);
        $membership->addFee($membershipFee);
        $this->assertEquals(10000, $membership->getTotal());
    }

    public function testDurationOptions(): void
    {
        $membership = new Membership();
        $this->assertEquals([], $membership->getDurationOptions());
        $this->assertInstanceOf(Membership::class, $membership->setDurationOptions([Membership::DURATION_ANNUAL]));
        $this->assertEquals([Membership::DURATION_ANNUAL], $membership->getDurationOptions());
    }

    public function testIsActive(): void
    {
        $membership = new Membership();
        $this->assertFalse($membership->getIsActive());
        $this->assertInstanceOf(Membership::class, $membership->setIsActive(true));
        $this->assertTrue($membership->getIsActive());
    }

    public function testIsGitSync(): void
    {
        $membership = new Membership();
        $this->assertFalse($membership->getIsGitSync());
        $this->assertInstanceOf(Membership::class, $membership->setIsGitSync(true));
        $this->assertTrue($membership->getIsGitSync());
    }

    public function testIsHidden(): void
    {
        $membership = new Membership();
        $this->assertFalse($membership->getIsHidden());
        $this->assertInstanceOf(Membership::class, $membership->setIsHidden(true));
        $this->assertTrue($membership->getIsHidden());
    }

    public function testPlayRightOnly(): void
    {
        $membership = new Membership();
        $this->assertFalse($membership->getPlayRightOnly());
        $this->assertInstanceOf(Membership::class, $membership->setPlayRightOnly(true));
        $this->assertTrue($membership->getPlayRightOnly());
    }

    public function testState(): void
    {
        $membership = new Membership();
        $this->assertEquals(Membership::STATE_DRAFT, $membership->getState());
        $this->assertInstanceOf(Membership::class, $membership->setState(Membership::STATE_PUBLISHED));
        $this->assertEquals(Membership::STATE_PUBLISHED, $membership->getState());
    }

    public function testClub(): void
    {
        $membership = new Membership();
        $club = new Club();
        $this->assertNull($membership->getClub());
        $this->assertInstanceOf(Membership::class, $membership->setClub($club));
        $this->assertEquals($club, $membership->getClub());
    }

    public function testPromotions(): void
    {
        $promotion1 = new Promotion();
        $promotion2 = new Promotion();
        $membership = new Membership();
        $this->assertInstanceOf(Collection::class, $membership->getPromotions());
        $this->assertInstanceOf(Membership::class, $membership->addPromotion($promotion1));
        $this->assertInstanceOf(Membership::class, $membership->addPromotion($promotion2));
        $this->assertInstanceOf(Membership::class, $membership->addPromotion($promotion2));
        $this->assertInstanceOf(Collection::class, $membership->getPromotions());
        $this->assertEquals(2, $membership->getPromotions()->count());
        Assert::allIsInstanceOf($membership->getPromotions(), Promotion::class);
        $this->assertInstanceOf(Membership::class, $membership->removePromotion($promotion2));
        $this->assertEquals(1, $membership->getPromotions()->count());
    }

    public function testMembershipCards(): void
    {
        $membership1 = new MembershipCard();
        $membership2 = new MembershipCard();
        $membership = new Membership();
        $this->assertInstanceOf(Collection::class, $membership->getMembershipCards());
        $this->assertInstanceOf(Membership::class, $membership->addMembershipCard($membership1));
        $this->assertInstanceOf(Membership::class, $membership->addMembershipCard($membership1));
        $this->assertInstanceOf(Membership::class, $membership->addMembershipCard($membership2));
        $this->assertInstanceOf(Collection::class, $membership->getMembershipCards());
        $this->assertEquals(2, $membership->countMembershipCards());
        Assert::allIsInstanceOf($membership->getMembershipCards(), MembershipCard::class);
        $this->assertInstanceOf(Membership::class, $membership->removeMembershipCard($membership2));
        $this->assertEquals(1, $membership->countMembershipCards());
    }
}
