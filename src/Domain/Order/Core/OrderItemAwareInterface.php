<?php

namespace App\Domain\Order\Core;

interface OrderItemAwareInterface
{
    public function getItem(): ?OrderItemInterface;

    public function setItem(?OrderItemInterface $item): void;
}
