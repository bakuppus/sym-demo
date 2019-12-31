<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker\Eligibility;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use DateTime;

final class PromotionDurationEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        $now = new DateTime();
        $startsAt = $promotion->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $promotion->getEndsAt();
        if (null !== $endsAt && $now > $endsAt) {
            return false;
        }

        return true;
    }
}