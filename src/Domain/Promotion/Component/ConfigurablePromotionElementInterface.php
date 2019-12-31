<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface ConfigurablePromotionElementInterface
{
    public function getType(): ?string;

    public function getConfiguration(): array;

    public function getPromotion(): ?PromotionInterface;
}