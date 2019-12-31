<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Model;

final class Total
{
    /** @var float */
    private $netSumTotal;

    /** @var float */
    private $vatTotal;

    /** @var float */
    private $total;

    public function getNetSumTotal(): float
    {
        return $this->netSumTotal;
    }

    public function setNetSumTotal(float $netSumTotal): void
    {
        $this->netSumTotal = $netSumTotal;
    }

    public function getVatTotal(): float
    {
        return $this->vatTotal;
    }

    public function setVatTotal(float $vatTotal): void
    {
        $this->vatTotal = $vatTotal;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getVatString(): string
    {
        return number_format($this->vatTotal, 2, ',', '');
    }

    public function getNetSumString(): string
    {
        return number_format($this->netSumTotal, 2, ',', '');
    }

    public function getTotalString(): string
    {
        return number_format($this->total, 2, ',', '');
    }
}