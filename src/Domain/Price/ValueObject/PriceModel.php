<?php

declare(strict_types=1);

namespace App\Domain\Price\ValueObject;

class PriceModel
{
    /** @var float */
    private $count;

    /** @var string */
    private $currency;

    public function getCount(): float
    {
        return $this->count;
    }

    public function setCount(float $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
