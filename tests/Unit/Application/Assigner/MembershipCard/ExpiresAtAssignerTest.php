<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Assigner\MembershipCard;

use App\Application\Assigner\MembershipCard\ExpiresAt\MembershipCardExpiresAtAssigner;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use PHPUnit\Framework\TestCase;
use DateTime;

final class ExpiresAtAssignerTest extends TestCase
{
    public function testAssignNull(): void
    {
        $membershipCard = new MembershipCard();

        $assigner = new MembershipCardExpiresAtAssigner();
        $assigner->assignExpiresAtIfNotSet($membershipCard);

        $this->assertNull($membershipCard->getExpiresAt());
    }

    public function testAssignWithAnnualDurationType(): void
    {
        $membershipCard = new MembershipCard();
        $membershipCard->setStartsAt(new DateTime());
        $membershipCard->setCalendarYear(new DateTime());
        $membershipCard->setDurationType(Membership::DURATION_ANNUAL);

        $assigner = new MembershipCardExpiresAtAssigner();
        $assigner->assignExpiresAtIfNotSet($membershipCard);

        $this->assertNotEmpty($membershipCard->getExpiresAt());
    }

    public function testAssignWith12MonthDurationType(): void
    {
        $membershipCard = new MembershipCard();
        $membershipCard->setStartsAt(new DateTime());
        $membershipCard->setCalendarYear(new DateTime());
        $membershipCard->setDurationType(Membership::DURATION_12_MONTH);

        $assigner = new MembershipCardExpiresAtAssigner();
        $assigner->assignExpiresAtIfNotSet($membershipCard);

        $this->assertNotEmpty($membershipCard->getExpiresAt());
    }
}
