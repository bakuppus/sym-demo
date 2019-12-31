<?php

namespace App\Domain\Payment\Component;

use Payum\Core\Model\GatewayConfigInterface as BaseGatewayConfigInterface;
use Payum\Core\Security\CryptedInterface;

interface GatewayConfigInterface extends BaseGatewayConfigInterface, CryptedInterface
{
}