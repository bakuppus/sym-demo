<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\ChargePayment;

use App\Domain\Payment\Payment;

class ChargePaymentCommand
{
    /** @var Payment */
    private $payment;

    /** @var array $options */
    private $options;

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}