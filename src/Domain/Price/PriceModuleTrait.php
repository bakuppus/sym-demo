<?php

declare(strict_types=1);

namespace App\Domain\Price;

trait PriceModuleTrait
{
    /** @var bool */
    protected $weekend;

    public function setWeekend(bool $isWeekend): void
    {
        $this->weekend = $isWeekend;
    }

    public function isWeekend(): bool
    {
        return $this->weekend;
    }
}
