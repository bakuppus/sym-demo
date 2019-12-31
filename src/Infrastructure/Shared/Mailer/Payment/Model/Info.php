<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Model;

use DateTimeImmutable;

final class Info
{
    /** @var DateTimeImmutable|null */
    private $transactionDate;

    /** @var string|null */
    private $transactionAmount;

    /** @var string */
    private $customerName;

    /** @var string */
    private $customerEmail;

    /** @var string|null */
    private $cardBrand;

    /** @var string|null */
    private $cardLastFour;

    public function getTransactionDate(): ?DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(?DateTimeImmutable $transactionDate): void
    {
        $this->transactionDate = $transactionDate;
    }

    public function getTransactionAmount(): ?string
    {
        return $this->transactionAmount;
    }

    public function setTransactionAmount(?string $transactionAmount): void
    {
        $this->transactionAmount = $transactionAmount;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function getCardBrand(): ?string
    {
        return $this->cardBrand;
    }

    public function setCardBrand(?string $cardBrand): void
    {
        $this->cardBrand = $cardBrand;
    }

    public function getCardLastFour(): ?string
    {
        return $this->cardLastFour;
    }

    public function setCardLastFour(?string $cardLastFour): void
    {
        $this->cardLastFour = $cardLastFour;
    }
}