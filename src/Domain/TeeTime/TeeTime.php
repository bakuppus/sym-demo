<?php

declare(strict_types=1);

namespace App\Domain\TeeTime;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Course\Course;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use App\Domain\Price\ValueObject\PriceModel as Price;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={}
 * )
 *
 * @ORM\Entity
 * @ORM\Table(indexes={
 *      @ORM\Index(name="IDX_53F0SVL7BIFCWJHA", columns={"golf_course_id", "from"}),
 *      @ORM\Index(name="IDX_63F0SVL7BIFCWJHB", columns={"golf_course_id", "to"}),
 *      @ORM\Index(name="IDX_73F0SVL7BIFCWJHC", columns={"golf_course_id", "version"}),
 *      @ORM\Index(name="IDX_83F0SVL7BIFCWJHD", columns={"version"})
 * })
 */
class TeeTime
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const MIN_AVAILABLE_SLOTS = 0;
    public const MAX_AVAILABLE_SLOTS = 4;

    public const STATUS_OPEN = 'open';
    public const STATUS_MEMBERS_ONLY = 'members_only';
    public const STATUS_START_FORBIDDEN = 'start_forbidden';
    public const STATUS_ON_SITE = 'on_site';
    public const STATUS_GROUP_BOOKING = 'group_booking';
    public const STATUS_TOURNAMENT = 'tournament';
    public const STATUS_BLOCKED = 'blocked';

    public const AVAILABLE_STATUS = [
        self::STATUS_OPEN,
        self::STATUS_MEMBERS_ONLY,
        self::STATUS_START_FORBIDDEN,
        self::STATUS_ON_SITE,
        self::STATUS_GROUP_BOOKING,
        self::STATUS_TOURNAMENT,
        self::STATUS_BLOCKED,
    ];

    public const BLOCKING_STATUS = [
        self::STATUS_START_FORBIDDEN,
        self::STATUS_ON_SITE,
        self::STATUS_GROUP_BOOKING,
        self::STATUS_TOURNAMENT,
        self::STATUS_BLOCKED,
    ];

    private const SATURDAY_WEEK_DAY_NUMBER = 6;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="teeTimes")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="`from`", type="datetime")
     */
    private $from;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="`to`", type="datetime")
     */
    private $to;

    /**
     * @var int
     *
     * @ORM\Column(name="`interval`", type="smallint")
     */
    private $interval;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isGolfIdRequired;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $availableSlots;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $maxSlots;

    /** @var Price */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes = null;

    /**
     * @var Player
     */
    private $player = null;

    /**
     * @var null|TeeTimeBookingParticipant[]
     */
    private $bookingParticipants = null;

    /**
     * @var int
     *
     * @ORM\Version
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->bookingParticipants = new ArrayCollection();
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function setFrom(DateTime $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function setTo(DateTime $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

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

    public function isGolfIdRequired(): bool
    {
        return $this->isGolfIdRequired;
    }

    public function setIsGolfIdRequired(bool $isGolfIdRequired): self
    {
        $this->isGolfIdRequired = $isGolfIdRequired;

        return $this;
    }

    public function getAvailableSlots(): int
    {
        return $this->availableSlots;
    }

    public function setAvailableSlots(int $availableSlots): self
    {
        $this->availableSlots = $availableSlots;

        return $this;
    }

    public function decreaseAvailableSlots(): self
    {
        --$this->availableSlots;

        return $this;
    }

    public function increaseAvailableSlots(): self
    {
        ++$this->availableSlots;

        return $this;
    }

    public function getMaxSlots(): int
    {
        return $this->maxSlots;
    }

    public function setMaxSlots(int $maxSlots): self
    {
        $this->maxSlots = $maxSlots;

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(?Price $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return Collection|TeeTimeBookingParticipant[]
     */
    public function getParticipants(): ?Collection
    {
        return $this->bookingParticipants;
    }

    public function setBookingParticipants(Collection $bookingParticipants): self
    {
        $this->bookingParticipants = $bookingParticipants;

        return $this;
    }

    public function isWeekend(): bool
    {
        return $this->getFrom()->format('N') >= self::SATURDAY_WEEK_DAY_NUMBER;
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
}
