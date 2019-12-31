<?php

namespace App\Infrastructure\Shared\Mailer\Order;

use App\Domain\Order\Core\OrderInterface;

interface OrderPaymentLinkGeneratorInterface
{
    public function generate(OrderInterface $order): string;
}
