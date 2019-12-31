<?php

namespace App\Domain\Payment\Component;

interface CreditCardInterface
{
    public function getToken(): string;

    public function setToken(string $token): void;

    public function getBrand(): string;

    public function setBrand(string $brand);

    public function getLastFour(): string;

    public function setLastFour(string $lastFour);
}