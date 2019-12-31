<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\RefundPayment;

use App\Domain\Payment\Core\PaymentInterface;

final class RefundPaymentCommand
{
    /** @var PaymentInterface */
    private $payment;

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function setPayment(PaymentInterface $payment): void
    {
        $this->payment = $payment;
    }
}