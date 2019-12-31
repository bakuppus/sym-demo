<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Applicator;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;

interface PromotionApplicatorInterface
{
    public function apply(PromotionSubjectInterface $subject, PromotionInterface $promotion): void;

    public function revert(PromotionSubjectInterface $subject, PromotionInterface $promotion): void;
}