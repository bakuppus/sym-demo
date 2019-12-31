<?php

namespace App\Domain\Price;

use App\Domain\Price\ValueObject\Price as PriceDTO;
use App\Domain\Price\Exception\PriceModuleParametersException;
use App\Domain\Price\Exception\PriceModuleSettingsException;
use DateTime;

/**
 * TODO: Refactor; This interface break ISP(Interface Segregation Principle)
 * The client should not implement an interface that it doesn't use
 */
interface PriceModuleSingleInheritanceInterface
{
    public function getTitle(): string;

    public function getDescription(): string;

    public function getModuleName(): string; /* This is our reference to module class */

    public function isActive(): bool;

    public function getSettings(): array;

    public function getOrder(): int;

    public function setWeekend(bool $isWeekend): void;

    public function isWeekend(): bool;

    public function calculate(PriceDTO $priceDto, DateTime $priceDateTime): void;

    /**
     * @throws PriceModuleSettingsException
     * @throws PriceModuleParametersException
     */
    public function validate(): void;
}
