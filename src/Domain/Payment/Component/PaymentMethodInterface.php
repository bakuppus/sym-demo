<?php

namespace App\Domain\Payment\Component;

interface PaymentMethodInterface
{
    public function getCode(): string;

    public function setCode(string $code): void;

    public function getEnvironment(): ?string;

    public function setEnvironment(?string $environment): void;

    public function isEnabled(): bool;

    public function setIsEnabled(bool $isEnabled): void;
}