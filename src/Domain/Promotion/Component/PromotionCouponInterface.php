<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use DateTimeInterface;

interface PromotionCouponInterface
{
    public function getUsageLimit(): ?int;

    public function setUsageLimit(?int $usageLimit): self;

    public function getUsed(): int;

    public function setUsed(int $used): self;

    public function incrementUsed(): self;

    public function decrementUsed(): self;

    public function getPromotion(): ?PromotionInterface;

    public function setPromotion(?PromotionInterface $promotion): self;

    public function getExpiresAt(): ?DateTimeInterface;

    public function setExpiresAt(?DateTimeInterface $expiresAt): self;

    public function isValid(): bool;
}