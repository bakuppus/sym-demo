<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="FK_WRRRZNAM50SPDF50", columns={"booking_id", "player_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class TeeTimeBookingRating
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var TeeTimeBooking
     *
     * @ORM\ManyToOne(targetEntity="TeeTimeBooking", inversedBy="ratings", fetch="EAGER")
     */
    private $booking;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="ratings", fetch="EAGER")
     */
    private $player;

    /**
     * @var TeeTimeBookingParticipant
     *
     * @ORM\ManyToOne(targetEntity="TeeTimeBookingParticipant", inversedBy="ratings", fetch="EAGER")
     */
    private $participant;

    /**
     * @var int $value
     *
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $value = 0;

    /**
     * @var TeeTimeBookingReview[]|Collection
     *
     * @ORM\OneToMany(targetEntity="TeeTimeBookingReview", mappedBy="rating", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

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

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(TeeTimeBookingReview $review): self
    {
        if (false === $this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setRating($this);
        }

        return $this;
    }
}