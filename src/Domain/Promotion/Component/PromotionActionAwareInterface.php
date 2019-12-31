<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use Doctrine\Common\Collections\Collection;

interface PromotionActionAwareInterface
{
    /**
     * @return Collection|PromotionActionInterface[]
     */
    public function getActions(): Collection;

    public function hasActions(): bool;

    public function hasAction(PromotionActionInterface $action): bool;

    public function addAction(PromotionActionInterface $action): self;

    public function removeAction(PromotionActionInterface $action): self;
}