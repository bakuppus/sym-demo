<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Strategy\ChargeRequestOptions;

interface ChargeRequestOptionsStrategyInterface
{
    public function validate(string $gatewayName): bool;

    public function getOptions(int $amount, array $options): array;
}