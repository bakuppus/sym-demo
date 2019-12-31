<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Model;

final class Item
{
    /** @var string */
    private $product;

    /** @var float */
    private $vatPercentage;

    /** @var float */
    private $netSum;

    /** @var float */
    private $vat;

    /** @var float */
    private $total;

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): void
    {
        $this->product = $product;
    }

    public function getVatPercentage(): float
    {
        return $this->vatPercentage;
    }

    public function setVatPercentage(float $vatPercentage): void
    {
        $this->vatPercentage = $vatPercentage;
    }

    public function getNetSum(): float
    {
        return $this->netSum;
    }

    public function setNetSum(float $netSum): void
    {
        $this->netSum = $netSum;
    }

    public function getVat(): float
    {
        return $this->vat;
    }

    public function setVat(float $vat): void
    {
        $this->vat = $vat;
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
        return number_format($this->vat, 2, ',', '');
    }

    public function getNetSumString(): string
    {
        return number_format($this->netSum, 2, ',', '');
    }

    public function getTotalString(): string
    {
        return number_format($this->total, 2, ',', '');
    }
}