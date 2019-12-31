<?php

namespace App\Domain\Order\Component;

interface OrderStateResolverInterface
{
    public function resolve(OrderInterface $order): void;
}
