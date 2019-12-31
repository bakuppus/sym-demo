<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\UpdatePaymentMethod;

use App\Domain\Payment\Core\PaymentInterface;

interface UpdatePaymentMethodStrategyInterface
{
    public function supports(string $methodName): bool;

    public function updatePaymentMethod(PaymentInterface $payment): PaymentInterface;
}