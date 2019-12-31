<?php

namespace App\Application\Assigner\Order\Token;

use App\Domain\Order\Core\OrderInterface;

interface OrderTokenAssignerInterface
{
    public function assignToken(OrderInterface $order): void;

    public function assignTokenIfNotSet(OrderInterface $order): void;
}
