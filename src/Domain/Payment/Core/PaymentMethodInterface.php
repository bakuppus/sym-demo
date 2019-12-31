<?php

namespace App\Domain\Payment\Core;

use App\Domain\Payment\Component\PaymentMethodInterface as ComponentPaymentMethodInterface;

interface PaymentMethodInterface extends ComponentPaymentMethodInterface
{
    public function getGatewayConfig(): GatewayConfigInterface;

    public function setGatewayConfig(GatewayConfigInterface $gatewayConfig): void;
}