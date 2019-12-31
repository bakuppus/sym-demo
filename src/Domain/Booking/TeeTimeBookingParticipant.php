<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Club\ClubPartnerType;
use App\Domain\Promotion\Component\MembershipInterface;
use App\Domain\Promotion\Component\MembershipPromotionSubjectInterface;
use App\Domain\Promotion\Component\PromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\OriginAwareInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\OriginTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="TeeTimeBookingParticipantLogEntry")
 * @Gedmo\SoftDeleteable
 */
class TeeTimeBookingParticipant implements OriginAwareInterface, MembershipPromotionSubjectInterface
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use OriginTrait;

    public const GOLF_CART_STATUS_NONE = 'none';
    public const GOLF_CART_STATUS_REQUESTED = 'requested';
    public const GOLF_CART_STATUS_PAID = 'paid';
    public const GOLF_CART_STATUS_CONFIRMED = 'confirmed';
    public const GOLF_CART_STATUS_DENIED = 'denied';

    public const GOLF_CART_AVAILABLE_STATUSES = [
        self::GOLF_CART_STATUS_NONE,
        self::GOLF_CART_STATUS_REQUESTED,
        self::GOLF_CART_STATUS_PAID,
        self::GOLF_CART_STATUS_CONFIRMED,
        self::GOLF_CART_STATUS_DENIED,
    ];

    /**
     * @var Player|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="bookingParticipants", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Gedmo\Versioned
     */
    private $player;

    /**
     * @var int
     *
     * @ORM\Column(type="float", precision=6, precision=20, options={"default":0})
     *
     * @Gedmo\Versioned
     */
    private $price;

    /**
     * @var Membership|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="bookingParticipants")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Gedmo\Versioned
     */
    private $ownerMembership;

    /**
     * @var TeeTimeBooking|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\TeeTimeBooking", inversedBy="bookingParticipants")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Gedmo\Versioned
     */
    private $booking;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $golfCartStatus = self::GOLF_CART_STATUS_NONE;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Gedmo\Versioned
     */
    private $isArrivalRegistration = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $paidAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Gedmo\Versioned
     */
    private $isOwner = true;

    /**
     * @var ClubPartnerType
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\ClubPartnerType")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Gedmo\Versioned
     */
    private $partnerType = null;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Gedmo\Versioned
     */
    private $isMember = true;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $gitId;

    /**
     * @var TeeTimeBookingRating[]|Collection
     *
     * @ORM\OneToMany(targetEntity="TeeTimeBookingRating", mappedBy="participant", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    private $ratings;

    /**
     * @var TeeTimeBookingReview[]|Collection
     *
     * @ORM\OneToMany(targetEntity="TeeTimeBookingReview", mappedBy="participant", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    private $reviews;

    /**
     * @var bool $isNotifiedForRating
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Gedmo\Versioned
     */
    private $isNotifiedForRating = false;

    /**
     * @var PromotionInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Promotion\Promotion")
     */
    private $promotions;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->createOrigin();
        $this->promotions = new ArrayCollection();
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    /*
     * TODO: Fix arg type (APP issue)
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOwnerMembership(): ?Membership
    {
        return $this->ownerMembership;
    }

    public function setOwnerMembership(Membership $ownerMembership): self
    {
        $this->ownerMembership = $ownerMembership;

        return $this;
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

    public function getGolfCartStatus(): string
    {
        return $this->golfCartStatus;
    }

    public function setGolfCartStatus(string $golfCartStatus): self
    {
        if (true === in_array($golfCartStatus, self::GOLF_CART_AVAILABLE_STATUSES)) {
            $this->golfCartStatus = $golfCartStatus;
        }

        return $this;
    }

    public function isArrivalRegistration(): bool
    {
        return $this->isArrivalRegistration;
    }

    public function setIsArrivalRegistration(bool $isArrivalRegistration): self
    {
        $this->isArrivalRegistration = $isArrivalRegistration;

        return $this;
    }

    public function setPaidAt(?DateTime $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getPaidAt(): ?DateTime
    {
        return $this->paidAt;
    }

    public function isOwner(): bool
    {
        return $this->isOwner;
    }

    public function setIsOwner(bool $isOwner): self
    {
        $this->isOwner = $isOwner;

        return $this;
    }

    public function setPartnerType(?ClubPartnerType $partnerType): self
    {
        $this->partnerType = $partnerType;

        return $this;
    }

    public function getPartnerType(): ?ClubPartnerType
    {
        return $this->partnerType;
    }

    public function isMember(): bool
    {
        return $this->isMember;
    }

    public function setIsMember(bool $isMember): self
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getGitId(): ?string
    {
        return $this->gitId;
    }

    public function setGitId(?string $gitId): self
    {
        $this->gitId = $gitId;

        return $this;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(TeeTimeBookingRating $rating): self
    {
        if (false === $this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setParticipant($this);
        }

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
            $review->setParticipant($this);
        }

        return $this;
    }

    public function isNotifiedForRating(): bool
    {
        return $this->isNotifiedForRating;
    }

    public function setIsNotifiedForRating(bool $isNotifiedForRating): self
    {
        $this->isNotifiedForRating = $isNotifiedForRating;

        return $this;
    }

    public function getMembership(): ?MembershipInterface
    {
        return $this->getOwnerMembership();
    }

    public function getPromotionSubjectTotal(): int
    {
        return (int) $this->getBooking()->getOriginPrice();
    }

    /**
     * @return Collection|PromotionInterface[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function hasPromotion(BasePromotionInterface $promotion): bool
    {
        return (bool) $this->promotions->contains($promotion);
    }

    public function addPromotion(BasePromotionInterface $promotion): PromotionSubjectInterface
    {
        if (false === $this->hasPromotion($promotion)) {
            $this->promotions->add($promotion);
        }

        return $this;
    }

    public function removePromotion(BasePromotionInterface $promotion): PromotionSubjectInterface
    {
        if (true === $this->hasPromotion($promotion)) {
            $this->promotions->removeElement($promotion);
        }

        return $this;
    }
}
