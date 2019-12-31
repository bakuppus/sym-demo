<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\CreatePayment;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Payment\Core\CreditCardInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Domain\Payment\Payment;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;

class CreatePaymentCommand implements CommandAwareInterface
{
    /**
     * @var OrderInterface
     *
     * @CommandBind(targetEntity="App\Domain\Order\Order")
     */
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

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order): CreatePaymentCommand
    {
        $this->order = $order;

        return $this;
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
        $payment = new Payment();

        $payment->setOrder($this->order);
        $payment->setPaymentMethod($this->paymentMethod);
        $payment->setCreditCard($this->creditCard);
        $payment->setCurrencyCode($this->currencyCode);
        $payment->setAmount($this->amount);
        $payment->setDetails($this->details);

        return $payment;
    }
}