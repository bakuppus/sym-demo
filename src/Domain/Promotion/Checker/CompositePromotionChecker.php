<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker;

use App\Domain\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

final class CompositePromotionChecker
{
    /** @var PromotionEligibilityCheckerInterface[] */
    private $promotionEligibilityCheckers;

    /**
     * @param PromotionEligibilityCheckerInterface[] $promotionEligibilityCheckers
     */
    public function __construct(iterable $promotionEligibilityCheckers)
    {
        Assert::notEmpty($promotionEligibilityCheckers);
        Assert::allIsInstanceOf($promotionEligibilityCheckers, PromotionEligibilityCheckerInterface::class);

        $this->promotionEligibilityCheckers = $promotionEligibilityCheckers;
    }

    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        foreach ($this->promotionEligibilityCheckers as $promotionEligibilityChecker) {
            if (false === $promotionEligibilityChecker->isEligible($promotionSubject, $promotion)) {
                return false;
            }
        }

        return true;
    }
}
