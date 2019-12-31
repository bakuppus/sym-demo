<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\ExpiresAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Membership;
use Cake\Chronos\Chronos;
use DateTimeInterface;
use Exception;

class TwelveMonthDurationTypeStrategy implements DurationTypeStrategyInterface
{
    public function validate(string $durationType): bool
    {
        return Membership::DURATION_12_MONTH === $durationType;
    }

    public function execute(MembershipCardInterface $membershipCard): ?DateTimeInterface
    {
        try {
            $startsAt = Chronos::instance($membershipCard->getStartsAt());

            return $startsAt->addMonths(12)->addDays(-1)->endOfDay();
        } catch (Exception $exception) {
            return null;
        }
    }
}
