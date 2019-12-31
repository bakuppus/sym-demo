<?php

namespace App\Domain\Price;

use App\Domain\Price\Exception\PriceModuleDynamicUpdateParametersException;
use App\Domain\Price\Exception\PriceModuleParametersException;
use App\Domain\Price\Exception\PriceModuleSettingsException;

/**
 * TODO: Refactor; This interface break ISP(Interface Segregation Principle)
 * The client should not implement an interface that it doesn't use
 *
 * @see App\Domain\Price\ValueObject\DemandDaily class
 */
interface PriceDynamicModuleInterface
{
    /**
     * @throws PriceModuleSettingsException
     */
    public function isValidSettings(): void;

    /**
     * @throws PriceModuleParametersException
     */
    public function isValidParameters(): void;

    /**
     * @throws PriceModuleDynamicUpdateParametersException
     */
    public function updateParameters(): void;
}
