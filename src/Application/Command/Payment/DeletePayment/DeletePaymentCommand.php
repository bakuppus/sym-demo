<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\DeletePayment;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Command\CommandAwareInterface;

class DeletePaymentCommand implements CommandAwareInterface
{
    /** @var PaymentInterface */
    private $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    /**
     * @return object|PaymentInterface
     */
    public function getResource(): object
    {
        return $this->payment;
    }
}