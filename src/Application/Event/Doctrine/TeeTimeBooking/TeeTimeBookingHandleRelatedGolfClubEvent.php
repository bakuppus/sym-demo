<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\TeeTimeBooking;

use App\Domain\Booking\TeeTimeBooking;

class TeeTimeBookingHandleRelatedGolfClubEvent
{
    /** @var TeeTimeBooking */
    protected $booking;

    public function __construct(TeeTimeBooking $booking)
    {
        $this->booking = $booking;
    }

    public function getBooking(): ?TeeTimeBooking
    {
        return $this->booking;
    }
}
