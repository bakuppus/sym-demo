<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Strategy\RefundRequestOptions;

interface RefundRequestOptionsStrategyInterface
{
    public function validate(string $gatewayName): bool;

    public function getOptions(int $amount, array $options): array;
}