<?php

namespace App\Domain\Payment\Core;

use App\Domain\Payment\Component\GatewayConfigInterface as BaseGatewayConfigInterface;
use Doctrine\Common\Collections\Collection;

interface GatewayConfigInterface extends BaseGatewayConfigInterface
{
    public function addPaymentMethod(PaymentMethodInterface $paymentMethod): void;

    public function getPaymentMethods(): Collection;
}