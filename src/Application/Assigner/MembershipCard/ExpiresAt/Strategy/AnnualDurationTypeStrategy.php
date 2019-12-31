<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\ExpiresAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Membership;
use Cake\Chronos\Chronos;
use DateTimeInterface;
use Exception;

class AnnualDurationTypeStrategy implements DurationTypeStrategyInterface
{
    public function validate(string $durationType): bool
    {
        return Membership::DURATION_ANNUAL === $durationType;
    }

    public function execute(MembershipCardInterface $membershipCard): ?DateTimeInterface
    {
        try {
            return Chronos::instance($membershipCard->getCalendarYear())->endOfYear();
        } catch (Exception $e) {
            return null;
        }
    }
}
