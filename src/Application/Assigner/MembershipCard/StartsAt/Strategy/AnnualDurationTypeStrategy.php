<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\StartsAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Membership;
use DateTimeInterface;

class AnnualDurationTypeStrategy implements DurationTypeStrategyInterface
{
    public function validate(string $durationType): bool
    {
        return Membership::DURATION_ANNUAL === $durationType;
    }

    public function execute(MembershipCardInterface $membershipCard): ?DateTimeInterface
    {
        return $membershipCard->getCalendarYear();
    }
}
