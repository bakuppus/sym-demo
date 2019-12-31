<?php

namespace App\Domain\Payment\Core;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Payment\Component\PaymentInterface as ComponentPaymentInterface;

interface PaymentInterface extends ComponentPaymentInterface
{
    public function getOrder(): OrderInterface;

    public function setOrder(OrderInterface $order): void;

    public function getPaymentMethod(): ?PaymentMethodInterface;

    public function setPaymentMethod(PaymentMethodInterface $paymentMethod): void;

    public function getCreditCard(): ?CreditCardInterface;

    public function setCreditCard(?CreditCardInterface $creditCard): void;
}