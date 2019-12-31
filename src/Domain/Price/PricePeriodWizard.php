<?php

declare(strict_types=1);

namespace App\Domain\Price;

class PricePeriodWizard
{
    /** @var PricePeriod */
    private $pricePeriod;

    /** @var bool */
    private $isWeekend;

    /** @var float */
    private $dailyOccupancy;

    public function getPricePeriod(): PricePeriod
    {
        return $this->pricePeriod;
    }

    public function setPricePeriod(PricePeriod $pricePeriod): self
    {
        $this->pricePeriod = $pricePeriod;

        return $this;
    }

    public function isWeekend(): bool
    {
        return (bool)$this->isWeekend;
    }

    public function setWeekend(bool $weekend): self
    {
        $this->isWeekend = (bool)$weekend;

        return $this;
    }

    public function getDailyOccupancy(): ?float
    {
        return $this->dailyOccupancy;
    }

    public function setDailyOccupancy(?float $dailyOccupancy): self
    {
        $this->dailyOccupancy = $dailyOccupancy;

        return $this;
    }
}
