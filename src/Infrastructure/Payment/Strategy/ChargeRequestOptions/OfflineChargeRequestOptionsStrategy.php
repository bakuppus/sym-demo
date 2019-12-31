<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Strategy\ChargeRequestOptions;

use App\Domain\Payment\GatewayConfig;
use Payum\Core\Request\GetHumanStatus;

class OfflineChargeRequestOptionsStrategy implements ChargeRequestOptionsStrategyInterface
{
    public function validate(string $gatewayName): bool
    {
        return GatewayConfig::GATEWAY_NAME_OFFLINE === $gatewayName;
    }

    public function getOptions(int $amount, array $options): array
    {
        return ['status' => GetHumanStatus::STATUS_CAPTURED];
    }
}