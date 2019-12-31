<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Promotion;

use App\Domain\Accounting\FeeUnit;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipFee;
use PHPUnit\Framework\TestCase;

final class MembershipFeeTest extends TestCase
{
    public function testVat(): void
    {
        $membershipFee = new MembershipFee();
        $this->assertNull($membershipFee->getVat());
        $this->assertInstanceOf(MembershipFee::class, $membershipFee->setVat(10));
        $this->assertEquals(10, $membershipFee->getVat());
    }

    public function testPrice(): void
    {
        $membershipFee = new MembershipFee();
        $this->assertNull($membershipFee->getPrice());
        $this->assertInstanceOf(MembershipFee::class, $membershipFee->setPrice(100));
        $this->assertEquals(100, $membershipFee->getPrice());
    }

    public function testMembership(): void
    {
        $membershipFee = new MembershipFee();
        $this->assertNull($membershipFee->getMembership());
        $this->assertInstanceOf(MembershipFee::class, $membershipFee->setMembership(new Membership()));
        $this->assertInstanceOf(Membership::class, $membershipFee->getMembership());

        $membership = new Membership();
        $this->assertIsInt($membership->getTotal());
        $this->assertInstanceOf(Membership::class, $membership->setTotal(10000));
        $this->assertEquals(0, $membership->getTotal());

        $membershipFee1 = new MembershipFee();
        $membershipFee1->setPrice(10000);
        $membership->addFee($membershipFee1);
        $this->assertEquals(10000, $membership->getTotal());

        $membershipFee2 = new MembershipFee();
        $membershipFee2->setPrice(5000);
        $membership->addFee($membershipFee2);
        $this->assertEquals(15000, $membership->getTotal());

        $membership->removeFee($membershipFee1);
        $this->assertEquals(5000, $membership->getTotal());
    }

    public function testFeeUnit(): void
    {
        $membershipFee = new MembershipFee();
        $this->assertNull($membershipFee->getFeeUnit());
        $this->assertInstanceOf(MembershipFee::class, $membershipFee->setFeeUnit(new FeeUnit()));
        $this->assertInstanceOf(FeeUnit::class, $membershipFee->getFeeUnit());
    }
}
