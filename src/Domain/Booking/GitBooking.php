<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GitBooking
{
    use AutoTrait;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $slotId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $code;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $bookingId;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default": 0})
     */
    protected $isCreatedOnSync = false;

    /**
     * @return string|null
     */
    public function getSlotId(): ?string
    {
        return $this->slotId;
    }

    /**
     * @param string|null $slotId
     *
     * @return GitBooking
     */
    public function setSlotId(?string $slotId): self
    {
        $this->slotId = $slotId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     *
     * @return GitBooking
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBookingId(): ?string
    {
        return $this->bookingId;
    }

    /**
     * @param string|null $bookingId
     *
     * @return GitBooking
     */
    public function setBookingId(?string $bookingId): self
    {
        $this->bookingId = $bookingId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return null !== $this->bookingId;
    }

    /**
     * @return bool
     */
    public function getIsCreatedOnSync(): bool
    {
        return $this->isCreatedOnSync;
    }

    /**
     * @param bool $isCreatedOnSync
     *
     * @return GitBooking
     */
    public function setIsCreatedOnSync(bool $isCreatedOnSync): self
    {
        $this->isCreatedOnSync = $isCreatedOnSync;

        return $this;
    }
}
