<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Assigner\MembershipCard;

use App\Application\Assigner\MembershipCard\ExpiresAt\MembershipCardExpiresAtAssigner;
use App\Application\Assigner\MembershipCard\StartsAt\MembershipCardStartsAtAssigner;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use PHPUnit\Framework\TestCase;
use DateTime;

final class StartsAtAssignerTest extends TestCase
{
    public function testAssignNull(): void
    {
        $membershipCard = new MembershipCard();

        $assigner = new MembershipCardStartsAtAssigner();
        $assigner->assignStartsAtIfNotSet($membershipCard);

        $this->assertNull($membershipCard->getStartsAt());
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
        $player = new Player();
        $membershipCard1 = new MembershipCard();
        $membershipCard1->setPlayer($player);
        $membershipCard2 = new MembershipCard();
        $membershipCard2->setPlayer($player);
        $membershipCard3 = new MembershipCard();
        $membershipCard3->setPlayer($player);

        $membershipCard = new MembershipCard();
        $membershipCard->setPlayer($player);
        $membershipCard->setCalendarYear(new DateTime());
        $membershipCard->setDurationType(Membership::DURATION_12_MONTH);

        $assigner = new MembershipCardStartsAtAssigner();
        $assigner->assignStartsAtIfNotSet($membershipCard);

        $this->assertNotEmpty($membershipCard->getStartsAt());
    }
}
