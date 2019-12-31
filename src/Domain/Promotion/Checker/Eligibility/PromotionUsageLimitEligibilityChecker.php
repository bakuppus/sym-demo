<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker\Eligibility;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;

final class PromotionUsageLimitEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if (null === $usageLimit = $promotion->getUsageLimit()) {
            return true;
        }

        return $promotion->getUsed() < $usageLimit;
    }
}