<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\UpdatePayment;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Payment\Core\CreditCardInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Infrastructure\Shared\Command\CommandAwareInterface;

class UpdatePaymentCommand implements CommandAwareInterface
{
    /** @var PaymentInterface */
    private $payment;

    /** @var OrderInterface */
    private $order;

    /** @var PaymentMethodInterface|null */
    private $paymentMethod;

    /** @var CreditCardInterface|null */
    private $creditCard;

    /** @var string */
    private $currencyCode;

    /** @var int */
    private $amount;

    /** @var array|null */
    private $details;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getPaymentMethod(): ?PaymentMethodInterface
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethodInterface $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getCreditCard(): ?CreditCardInterface
    {
        return $this->creditCard;
    }

    public function setCreditCard(?CreditCardInterface $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(?array $details): void
    {
        $this->details = $details;
    }

    /**
     * @return object|PaymentInterface
     */
    public function getResource(): object
    {
        $payment = $this->payment;

        $payment->setOrder($this->order);
        $payment->setPaymentMethod($this->paymentMethod);
        $payment->setCreditCard($this->creditCard);
        $payment->setCurrencyCode($this->currencyCode);
        $payment->setAmount($this->amount);
        $payment->setDetails($this->details);

        return $payment;
    }
}