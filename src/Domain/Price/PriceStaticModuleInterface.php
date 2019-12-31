<?php

namespace App\Domain\Price;

use App\Domain\Price\Exception\PriceModuleSettingsException;

interface PriceStaticModuleInterface
{
    /**
     * @throws PriceModuleSettingsException
     */
    public function isValidSettings(): void;
}
