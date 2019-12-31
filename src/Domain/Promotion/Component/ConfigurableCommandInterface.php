<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;

interface ConfigurableCommandInterface
{
    /**
     * @param array $configuration
     *
     * @throws InvalidConfigurablePromotionException
     */
    public function validateConfiguration(array $configuration): void;

    public function getType(): string;
}
