<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Player\PlayerPaymentMethod;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Transaction
{
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCEED = 'succeed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PENDING = 'pending';

    public const TYPE_CHARGE = 'charge';
    public const TYPE_REFUND = 'refund';

    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var TeeTimeBooking $booking
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\TeeTimeBooking")
     */
    private $booking;

    /**
     * @var TeeTimeBookingParticipant $participant
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\TeeTimeBookingParticipant")
     */
    private $participant;

    /**
     * @var PlayerPaymentMethod $paymentMethod
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\PlayerPaymentMethod")
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $braintreeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $errorMessage = null;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isPaidFullPrice = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getBooking(): TeeTimeBooking
    {
        return $this->booking;
    }

    public function setBooking(TeeTimeBooking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    public function getPaymentMethod(): PlayerPaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PlayerPaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBraintreeId(): string
    {
        return $this->braintreeId;
    }

    public function setBraintreeId(string $braintreeId): self
    {
        $this->braintreeId = $braintreeId;

        return $this;
    }

    public function setErrorMessage(?string $message): self
    {
        $this->errorMessage = $message;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function isPaidFullPrice(): bool
    {
        return $this->isPaidFullPrice;
    }

    public function setIsPaidFullPrice(bool $isPaidFullPrice): self
    {
        $this->isPaidFullPrice = $isPaidFullPrice;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getParticipant(): TeeTimeBookingParticipant
    {
        return $this->participant;
    }

    public function setParticipant(TeeTimeBookingParticipant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
