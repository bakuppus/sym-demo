<?php

declare(strict_types=1);

namespace App\Domain\Order\Core;

use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Domain\Order\Component\OrderInterface as BaseOrderInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Player\Player;
use Doctrine\Common\Collections\Collection;

interface OrderInterface extends BaseOrderInterface
{
    public function getClub(): Club;

    public function setClub(Club $club): void;

    public function getCourse(): ?Course;

    public function setCourse(?Course $course): void;

    public function getCustomer(): ?Player;

    public function setCustomer(?Player $user): void;

    public function getCurrencyCode(): string;

    public function setCurrencyCode(string $currencyCode): void;

    public function getLocaleCode(): string;

    public function setLocaleCode(string $localeCode): void;

    public function getPaymentState(): string;

    public function setPaymentState(string $paymentState): void;

    /**
     * @return Collection|OrderItemInterface[]
     */
    public function getItems(): Collection;

    /**
     * @return Collection|PaymentInterface[]
     */
    public function getPayments(): Collection;

    public function addPayment(PaymentInterface $payment): void;
}
