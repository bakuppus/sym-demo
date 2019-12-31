<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\ChargeResponse;

use App\Domain\Payment\Core\PaymentInterface;

interface ChargeResponseStrategyInterface
{
    public function updatePaymentByResponse(PaymentInterface $payment, array $responseData): void;

    public function validate(string $status): bool;
}