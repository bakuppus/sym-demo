<?php

namespace App\Domain\Order\Component;

interface OrderAwareInterface
{
    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;
}
