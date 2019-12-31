<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface PromotionCouponSubjectInterface extends PromotionSubjectInterface
{
    public function getPromotionCoupon(): ?PromotionCouponInterface;
}