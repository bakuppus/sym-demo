<?php

namespace App\Domain\Order\Core;

use App\Domain\Order\Component\OrderAwareInterface as BaseOrderAwareInterface;
use App\Domain\Order\Component\OrderInterface as BaseOrderInterface;

interface OrderAwareInterface extends BaseOrderAwareInterface
{
    /**
     * @return BaseOrderInterface|OrderInterface|null
     */
    public function getOrder(): ?BaseOrderInterface;

    /**
     * @param BaseOrderInterface|OrderInterface|null $order
     */
    public function setOrder(?BaseOrderInterface $order): void;
}
