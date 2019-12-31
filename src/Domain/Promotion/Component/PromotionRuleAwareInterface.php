<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use Doctrine\Common\Collections\Collection;

interface PromotionRuleAwareInterface
{
    /**
     * @return Collection|PromotionRuleInterface[]
     */
    public function getRules(): Collection;

    public function hasRules(): bool;

    public function hasRule(PromotionRuleInterface $rule): bool;

    public function addRule(PromotionRuleInterface $rule): self;

    public function removeRule(PromotionRuleInterface $rule): self;
}