<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Utils;

use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;

trait BraintreeGatewayNameGetterTrait
{
    public function getBraintreeGatewayName(bool $isSandbox): string
    {
        $gatewayName = true === $isSandbox?
            BraintreeGateway::SANDBOX_GATEWAY_NAME :
            BraintreeGateway::PRODUCTION_GATEWAY_NAME;

        return $gatewayName;
    }
}