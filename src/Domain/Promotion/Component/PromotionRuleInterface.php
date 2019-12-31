<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface PromotionRuleInterface extends ConfigurablePromotionElementInterface
{
    public function setType(?string $type): self;

    public function setConfiguration(array $configuration): self;

    public function setPromotion(?PromotionInterface $promotion): self;
}