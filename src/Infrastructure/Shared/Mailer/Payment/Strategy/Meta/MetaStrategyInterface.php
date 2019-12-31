<?php

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Meta;

use App\Domain\Payment\Core\PaymentInterface;

interface MetaStrategyInterface
{
    public function supports(PaymentInterface $payment): bool;

    public function getMeta(PaymentInterface $payment): array;
}