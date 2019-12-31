<?php

declare(strict_types=1);

namespace  App\Domain\Booking;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class BookingCancelReservationJob
 *
 * @package App\Domain\Booking
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class BookingCancelReservationJob
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var TeeTimeBooking
     *
     * @ORM\OneToOne(targetEntity="TeeTimeBooking", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $booking;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $jobKey;

    public function getBooking(): TeeTimeBooking
    {
        return $this->booking;
    }

    public function setBooking(TeeTimeBooking $booking): BookingCancelReservationJob
    {
        $this->booking = $booking;

        return $this;
    }

    public function getJobKey(): string
    {
        return $this->jobKey;
    }

    public function setJobKey(string $key): BookingCancelReservationJob
    {
        $this->jobKey = $key;

        return $this;
    }
}