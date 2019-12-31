<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Strategy\RefundRequestOptions;

use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Payment\Payum\Braintree\Utils\BraintreeMoneyConverter;

class BraintreeRefundRequestOptionsStrategy implements RefundRequestOptionsStrategyInterface
{
    use BraintreeMoneyConverter;

    public function validate(string $gatewayName): bool
    {
        $isGatewayNameBraintree = BraintreeGateway::PRODUCTION_GATEWAY_NAME === $gatewayName ||
            BraintreeGateway::SANDBOX_GATEWAY_NAME === $gatewayName;

        return true === $isGatewayNameBraintree;
    }

    public function getOptions(int $amount, array $options): array
    {
        $requiredOptions = ['amount' => $this->getAmountForBraintree($amount)];

        $options = array_merge($requiredOptions, $options);

        return $options;
    }
}