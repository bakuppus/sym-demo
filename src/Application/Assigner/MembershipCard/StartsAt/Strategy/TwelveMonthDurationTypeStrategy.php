<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\StartsAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use Cake\Chronos\Chronos;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;
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
            $now = Chronos::now();
            $membershipCards = $membershipCard
                ->getPlayer()
                ->getMembershipCards()
                ->filter(function (MembershipCard $card) use ($membershipCard): bool {
                    return $card !== $membershipCard;
                });

            $previousExpiresAt = null;

            if (false === $membershipCards->isEmpty()) {
                $criteria = Criteria::create()->orderBy(['expires_at' => Criteria::DESC]);
                $sortedMembershipCards = $membershipCards->matching($criteria);
                $membershipCard = $sortedMembershipCards->first();
                $previousExpiresAt = Chronos::instance($membershipCard->getExpiresAt());
            }

            if (null !== $previousExpiresAt && true === $now->lessThanOrEquals($previousExpiresAt)) {
                return $previousExpiresAt->addSecond();
            }

            return $now;
        } catch (Exception $exception) {
            return null;
        }
    }
}
