<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\SendReceipt;

use App\Domain\Payment\Core\PaymentInterface;

class SendReceiptCommand
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