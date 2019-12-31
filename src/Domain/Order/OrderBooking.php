<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Booking\TeeTimeBookingParticipant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OrderBooking extends Order
{
    /**
     * @var TeeTimeBooking
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\TeeTimeBooking")
     */
    private $booking;

    /**
     * @var TeeTimeBookingParticipant
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\TeeTimeBookingParticipant")
     */
    private $bookingParticipant;

    public function getBooking(): TeeTimeBooking
    {
        return $this->booking;
    }

    public function setBooking(TeeTimeBooking $booking): void
    {
        $this->booking = $booking;
    }

    public function getBookingParticipant(): TeeTimeBookingParticipant
    {
        return $this->bookingParticipant;
    }

    public function setBookingParticipant(TeeTimeBookingParticipant $bookingParticipant): void
    {
        $this->bookingParticipant = $bookingParticipant;
    }
}
