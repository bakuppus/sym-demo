<?php

namespace App\Domain\Order\Component;

interface OrderItemInterface extends OrderAwareInterface/*, AdjustableInterface*/
{
    public function getQuantity(): int;

    public function getTotal(): int;
}
