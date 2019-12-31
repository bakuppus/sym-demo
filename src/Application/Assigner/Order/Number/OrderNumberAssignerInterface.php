<?php

namespace App\Application\Assigner\Order\Number;

use App\Domain\Order\Core\OrderInterface;

interface OrderNumberAssignerInterface
{
    public function assignNumber(OrderInterface $order): void;
}
