<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Order;

use App\Domain\Order\Core\OrderInterface;

interface OrderPaymentLinkSenderInterface
{
    public function send(OrderInterface $order): void;
}
