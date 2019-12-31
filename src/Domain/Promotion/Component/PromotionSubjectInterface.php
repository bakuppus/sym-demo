<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use Doctrine\Common\Collections\Collection;

interface PromotionSubjectInterface
{
    public function getPromotionSubjectTotal(): int;

    /**
     * @return Collection|PromotionInterface[]
     */
    public function getPromotions(): Collection;

    public function hasPromotion(PromotionInterface $promotion): bool;

    public function addPromotion(PromotionInterface $promotion): self;

    public function removePromotion(PromotionInterface $promotion): self;
}