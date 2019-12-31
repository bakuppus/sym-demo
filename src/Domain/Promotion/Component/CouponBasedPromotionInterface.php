<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use Doctrine\Common\Collections\Collection;

interface CouponBasedPromotionInterface
{
    public function isCouponBased(): bool;

    public function setCouponBased(?bool $couponBased): self;

    /**
     * @return Collection|PromotionCouponInterface[]
     */
    public function getCoupons(): Collection;

    public function hasCoupon(PromotionCouponInterface $coupon): bool;

    public function hasCoupons(): bool;

    public function addCoupon(PromotionCouponInterface $coupon): self;

    public function removeCoupon(PromotionCouponInterface $coupon): self;
}