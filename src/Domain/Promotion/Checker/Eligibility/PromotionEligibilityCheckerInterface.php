<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker\Eligibility;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;

interface PromotionEligibilityCheckerInterface
{
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool;
}