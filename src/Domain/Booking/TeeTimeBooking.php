<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Club\ClubPartnerType;
use App\Domain\Course\Course;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\OriginAwareInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\OriginTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Exception;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={
 *      @ORM\Index(name="IDX_PWKTJLAPRH1LHIQ4", columns={"start_time", "status", "golf_course_id", "deleted_at"})
 * })
 * @Gedmo\SoftDeleteable
 * @Gedmo\Loggable(logEntryClass="TeeTimeBookingLogEntry")
 */
class TeeTimeBooking implements OriginAwareInterface
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use OriginTrait;

    public const FRIEND_DISCOUNT = 30;
    public const MEMBER_DISCOUNT = 100;
    public const NON_MEMBER_DISCOUNT = 0;

    public const STATUS_ON_RESERVATION = 'on reservation';
    public const STATUS_ON_PENDING = 'on pending';
    public const STATUS_CONFIRMED = 'waiting for payment';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_PAID = 'paid';
    public const STATUS_PAY_ON_SITE = 'pay on site';

    public const SOURCE_BROWSER = 'browser';
    public const SOURCE_MOBILE = 'mobile';
    public const SOURCE_WIDGET = 'widget';

    /**
     * @var TeeTimeBookingParticipant
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Booking\TeeTimeBookingParticipant")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Gedmo\Versioned
     */
    private $participantOwner;

    /**
     * @var Player|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="teeTimeBookings")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Gedmo\Versioned
     */
    private $owner;

    /**
     * @var TeeTimeBookingParticipant[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Domain\Booking\TeeTimeBookingParticipant",
     *      mappedBy="booking",
     *      fetch="EAGER",
     *      cascade={"persist"}
     * )
     */
    private $bookingParticipants;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    private $startTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    private $endTime;

    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="teeTimeBookings")
     * @ORM\JoinColumn(name="golf_course_id", onDelete="SET NULL")
     *
     * @Gedmo\Versioned
     */
    private $course;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, options={"default":0})
     *
     * @Gedmo\Versioned
     */
    private $totalPrice;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, options={"default":0})
     *
     * @Gedmo\Versioned
     */
    private $originPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Gedmo\Versioned
     */
    private $status;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $paidAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $source;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $browser;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $platform;

    /**
     * @internal Add extra membership validation rule message (to not break app and web compatibility)
     *
     * @var array
     */
    private $validationErrors = [];

    /**
     * @var GitBooking|null
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Booking\GitBooking", cascade={"persist", "remove"})
     */
    private $gitBooking;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isWasPaidAfterConfirmation = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $creator;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Version
     */
    protected $version;

    /**
     * @var ClubPartnerType
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\ClubPartnerType")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $partnerType = null;

    /**
     * @var TeeTimeBookingRating[]|Collection
     *
     * @ORM\OneToMany(targetEntity="TeeTimeBookingRating", mappedBy="booking", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    protected $ratings;

    /**
     * @var TeeTimeBookingReview[]|Collection
     *
     * @ORM\OneToMany(targetEntity="TeeTimeBookingReview", mappedBy="booking", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    protected $reviews;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->bookingParticipants = new ArrayCollection();
        $this->createOrigin();
        /*$this->detectSource();*/

        $this->ratings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getParticipantOwner(): TeeTimeBookingParticipant
    {
        return $this->participantOwner;
    }

    public function setParticipantOwner(TeeTimeBookingParticipant $participantOwner): self
    {
        $this->participantOwner = $participantOwner;

        return $this;
    }

    /**
     * @return TeeTimeBookingParticipant[]|Collection|ArrayCollection|PersistentCollection
     */
    public function getActiveBookingParticipants()
    {
        return $this->bookingParticipants->filter(function (TeeTimeBookingParticipant $participant): bool {
            return false === $participant->isDeleted();
        });
    }

    /**
     * @return TeeTimeBookingParticipant[]|Collection|ArrayCollection|PersistentCollection
     */
    public function getBookingParticipants()
    {
        return $this->bookingParticipants;
    }

    public function setBookingParticipants(ArrayCollection $bookingParticipants): self
    {
        $this->bookingParticipants = $bookingParticipants;

        return $this;
    }

    public function addBookingParticipant(TeeTimeBookingParticipant $participant): self
    {
        if (false === $this->getBookingParticipants()->contains($participant)) {
            $this->getBookingParticipants()->add($participant);
            $participant->setBooking($this);
        }

        return $this;
    }

    public function removeBookingParticipant(TeeTimeBookingParticipant $participant): self
    {
        if (true === $this->getBookingParticipants()->contains($participant)) {
            $this->getBookingParticipants()->removeElement($participant);
            $participant->setBooking(null);
        }

        return $this;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getStartTimestamp(): int
    {
        return $this->startTime->getTimestamp();
    }

    public function setStartTime(DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function getEndTimestamp(): int
    {
        return $this->endTime->getTimestamp();
    }

    public function setEndTime(DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): self
    {
        $this->totalPrice = $totalPrice ?? 0;

        return $this;
    }

    public function getOriginPrice(): float
    {
        return $this->originPrice;
    }

    public function setOriginPrice(float $originPrice): self
    {
        $this->originPrice = $originPrice;

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

    public function isConfirmed(): bool
    {
        return self::STATUS_CONFIRMED === $this->getStatus();
    }

    public function isCanceled(): bool
    {
        return self::STATUS_CANCELED === $this->getStatus();
    }

    public function isOnReservation(): bool
    {
        return self::STATUS_ON_RESERVATION === $this->getStatus();
    }

    public function isPaid(): bool
    {
        return self::STATUS_PAID === $this->getStatus();
    }

    public function isPayOnSite(): bool
    {
        return self::STATUS_PAY_ON_SITE === $this->getStatus();
    }

    public function getOwner(): ?Player
    {
        return $this->owner;
    }

    public function setOwner(?Player $owner): self
    {
        $this->owner = $owner;

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

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function setValidationErrors(array $validationErrors): self
    {
        $this->validationErrors = $validationErrors;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(?string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * @return TeeTimeBooking
     *
     * @deprecated
     */
//    public function detectSource(): self
//    {
//        $agent = new Agent();
//        $source = $agent->isMobile() ? self::SOURCE_MOBILE : self::SOURCE_BROWSER;
//        $browser = $agent->browser() ?: null;
//        $platform = $agent->platform() ?: null;
//
//        $this
//            ->setSource($source)
//            ->setBrowser($browser)
//            ->setPlatform($platform);
//
//        return $this;
//    }

    public function statuses(): array
    {
        return [
            self::STATUS_ON_RESERVATION,
            self::STATUS_ON_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELED,
            self::STATUS_DELETED,
            self::STATUS_PAID,
            self::STATUS_PAY_ON_SITE,
        ];
    }

    public function getGitBooking(): ?GitBooking
    {
        return $this->gitBooking;
    }

    public function setGitBooking(?GitBooking $gitBooking): self
    {
        $this->gitBooking = $gitBooking;

        return $this;
    }

    public function isGitBooking(): bool
    {
        return null !== $this->gitBooking;
    }

    public function isUpcoming(): bool
    {
        return false === $this->isCanceled() && false === $this->isDeleted() && new DateTime('now') < $this->startTime;
    }

    public function isPaidWithZeroPrice(): bool
    {
        return self::STATUS_PAID === $this->status && true === $this->isWasPaidAfterConfirmation;
    }

    public function isCanAccommodateParticipants(): bool
    {
        return true === $this->isPayOnSite() ||
            true === $this->isOnReservation() ||
            true === $this->isConfirmed() ||
            true === $this->isPaidWithZeroPrice();
    }

    public function isWasPaidAfterConfirmation(): bool
    {
        return $this->isWasPaidAfterConfirmation;
    }

    public function setIsWasPaidAfterConfirmation(bool $isWasPaidAfterConfirmation): self
    {
        $this->isWasPaidAfterConfirmation = $isWasPaidAfterConfirmation;

        return $this;
    }

    public function isCreatedOnGitSync(): bool
    {
        if (false === $this->isGitBooking()) {
            return false;
        }

        return $this->getGitBooking()->getIsCreatedOnSync();
    }

    public function isConfirmedForFact(): bool
    {
        return in_array($this->getStatus(), [
            TeeTimeBooking::STATUS_CONFIRMED,
            TeeTimeBooking::STATUS_PAID,
            TeeTimeBooking::STATUS_PAY_ON_SITE,
        ]);
    }

    public function getCreator(): string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

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

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

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
            $rating->setBooking($this);
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
            $this->ratings->add($review);
            $review->setBooking($this);
        }

        return $this;
    }
}
