<?php

namespace App\Domain\Payment\Component;

interface PaymentInterface
{
    public function getCurrencyCode(): string;

    public function setCurrencyCode(string $currencyCode): void;

    public function getAmount(): int;

    public function setAmount(int $amount): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getDetails(): ?array;

    public function setDetails(?array $details): void;
}