<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class TeeTimeBookingReview
{
    public const TYPE_OTHER = 'other';
    public const TYPE_GREENS = 'greens';
    public const TYPE_FAIRWAYS = 'fairways';
    public const TYPE_BUNKERS = 'bunkers';
    public const TYPE_TEES = 'tees';

    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var TeeTimeBooking
     *
     * @ORM\ManyToOne(targetEntity="TeeTimeBooking", inversedBy="reviews", fetch="EAGER")
     */
    private $booking;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="reviews", fetch="EAGER")
     */
    private $player;

    /**
     * @var TeeTimeBookingParticipant
     *
     * @ORM\ManyToOne(targetEntity="TeeTimeBookingParticipant", inversedBy="reviews", fetch="EAGER")
     */
    private $participant;

    /**
     * @var TeeTimeBookingRating
     *
     * @ORM\ManyToOne(targetEntity="TeeTimeBookingRating", inversedBy="reviews", fetch="EAGER")
     */
    private $rating;

    /**
     * @var string|null $type
     *
     * @ORM\Column(type="string", nullable=true, length=30)
     */
    private $type;

    /**
     * @var string|null $reason
     *
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $reason;

    public function getBooking(): ?TeeTimeBooking
    {
        return $this->booking;
    }

    public function setBooking(?TeeTimeBooking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getParticipant(): ?TeeTimeBookingParticipant
    {
        return $this->participant;
    }

    public function setParticipant(?TeeTimeBookingParticipant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getRating(): ?TeeTimeBookingRating
    {
        return $this->rating;
    }

    public function setRating(?TeeTimeBookingRating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}