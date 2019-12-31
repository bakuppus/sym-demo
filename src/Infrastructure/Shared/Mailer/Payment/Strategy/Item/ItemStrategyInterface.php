<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Item;

use App\Domain\Payment\Core\PaymentInterface;

interface ItemStrategyInterface
{
    public function supports(PaymentInterface $payment): bool;

    public function getItems(PaymentInterface $payment): array;
}