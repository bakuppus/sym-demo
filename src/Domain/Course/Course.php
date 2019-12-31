<?php

declare(strict_types=1);

namespace App\Domain\Course;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Club\Club;
use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Membership\MembershipCourse;
use App\Domain\Player\Player;
use App\Domain\Price\PricePeriod;
use App\Domain\TeeTime\Period;
use App\Domain\TeeTime\TeeSheetLink;
use App\Domain\TeeTime\TeeTime;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Type\Spatial\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Application\Command\Course\Crm\UpdateCourse\UpdateCourseCommand;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\OrderDistanceFilter;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\DistanceFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\OrderFilter;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\RangeFilter;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\CourseTeeTimeSourceFilter;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\CalculateDistanceFilter;

/**
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "path"="/crm/courses/{id}",
 *              "input"=UpdateCourseCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_course"}},
 *              "denormalization_context"={"groups"={"Default", "update_course"}}
 *          },
 *     },
 *     normalizationContext={"groups"={"Default", "get_course", "list_course"}}
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="golf_courses", uniqueConstraints={@ORM\UniqueConstraint(name="FK_TUNMSTW9CNA96MHW", columns={"golf_club_id", "name"})})
 * @ApiFilter(OrderFilter::class, properties={"teeTimes.id"})
 * @ApiFilter(OrderDistanceFilter::class, properties={"lonlat"})
 * @ApiFilter(DistanceFilter::class, properties={"lonlat"})
 * @ApiFilter(TermFilter::class, properties={"tee_times.status"})
 * @ApiFilter(RangeFilter::class, properties={"teeTimes.from", "teeTimes.availableSlots"})
 * @ApiFilter(CourseTeeTimeSourceFilter::class, properties={"is_git_id_required"})
 * @ApiFilter(CalculateDistanceFilter::class, properties={"calculateDistance"})
 */
class Course implements ClubAwareInterface
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    public const SOURCE_GIT = 'git';
    public const SOURCE_SWEETSPOT = 'sweetspot';
    public const SOURCE_NONE = 'none';

    public const TEE_TIME_SOURCES = [
        self::SOURCE_GIT,
        self::SOURCE_SWEETSPOT,
        self::SOURCE_NONE,
    ];

    const METERS_NUMBER = 1000;
    const PRECISION = 3;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Groups({"list_course"})
     */
    private $gitId;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="courses", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="golf_club_id")
     *
     * @Groups({"list_course"})
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     *
     * @Groups({"list_course"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"list_course"})
     */
    private $description;

    /**
     * @var Point|null
     *
     * @ORM\Column(type="point", nullable=true)
     *
     * @Groups({"list_course"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="object",
     *             "example"={
     *                  "latitude": 0,
     *                  "longitude": 0
     *             }
     *         }
     *     }
     * )
     */
    private $lonlat;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Groups({"list_course"})
     */
    private $bookingInformation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Groups({"list_course"})
     */
    private $customDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Groups({"list_course"})
     */
    private $customBookingInformation;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups({"list_course"})
     */
    private $isUseCustomInformation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Groups({"list_course"})
     */
    private $customDescriptionShort;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Groups({"list_course"})
     */
    private $customBookingInformationShort;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups({"list_course"})
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups({"list_course"})
     */
    private $isUseDynamicPricing;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"list_course"})
     */
    private $bookingType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     *
     * @Groups({"list_course"})
     */
    private $teeTimeSource;

    /**
     * @var MembershipCourse[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\MembershipCourse", mappedBy="course")
     */
    private $membershipCourses;

    /**
     * @var Period[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\TeeTime\Period", mappedBy="course", cascade={"persist"})
     */
    private $periods;

    /**
     * @var TeeTime[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\TeeTime\TeeTime", mappedBy="course", cascade={"persist"})
     */
    private $teeTimes;

    /**
     * @var PricePeriod[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Price\PricePeriod", mappedBy="course", cascade={"persist", "remove"})
     */
    private $pricePeriods;

    /**
     * @var TeeTimeBooking[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Booking\TeeTimeBooking", mappedBy="course", cascade={"persist"})
     */
    private $teeTimeBookings;

    /**
     * @var Player[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Player\Player", mappedBy="favoriteGolfCourses")
     */
    private $players;

    /**
     * @var float
     *
     * @Groups({"list_course"})
     */
    private $distance = 0;

    /**
     * @var CourseImage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Course\CourseImage", mappedBy="course", cascade={"persist"})
     */
    private $images;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_course"})
     */
    private $isCanPay = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Groups({"list_course"})
     */
    private $isArrivalRegistration = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     *
     * @Groups({"list_course"})
     */
    private $juniorDiscount;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"list_course"})
     */
    private $displayTeeTimeDays;

    /**
     * @var TeeSheetLink[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\TeeTime\TeeSheetLink", mappedBy="course", cascade={"persist"},
     *     fetch="EXTRA_LAZY")
     */
    private $teeSheetLinks;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_course"})
     */
    private $isUseBookingConfirmationExpiration = false;

    /**
     * @var CourseGuide
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Course\CourseGuide", mappedBy="course", fetch="EXTRA_LAZY")
     */
    protected $guide;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Groups({"list_course"})
     */
    private $isStubPlayersEnabled = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $bookedFromAppDiscount;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private $isPayOnSiteEnabled = true;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $searchField;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $caddeeId;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isArrivalRegistrationAfterSchedule = false;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->membershipCourses = new ArrayCollection();
        $this->periods = new ArrayCollection();
        $this->teeTimes = new ArrayCollection();
        $this->pricePeriods = new ArrayCollection();
        $this->teeTimeBookings = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->teeSheetLinks = new ArrayCollection();
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

    public function getClub(): ?Club
    {
        return $this->club;
    }

    /**
     * @param Club $club
     *
     * @return ClubAwareInterface|Course
     */
    public function setClub(?Club $club): ClubAwareInterface
    {
        $this->club = $club;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLonlat(): ?Point
    {
        if (null !== $this->lonlat) {
            return new Point($this->lonlat->getLongitude(), $this->lonlat->getLatitude());
        }

        return new Point(0, 0);
    }

    public function setLonlat(?Point $lonlat): self
    {
        $this->lonlat = $lonlat;

        return $this;
    }

    public function setLonlatFromParams(float $longitude, float $latitude): self
    {
        $lonlat = new Point($longitude, $latitude);

        $this->lonlat = $lonlat;

        return $this;
    }

    public function getBookingInformation(): string
    {
        return $this->bookingInformation;
    }

    public function setBookingInformation(string $bookingInformation): self
    {
        $this->bookingInformation = $bookingInformation;

        return $this;
    }

    public function getCustomDescription(): string
    {
        return $this->customDescription;
    }

    public function setCustomDescription(string $customDescription): self
    {
        $this->customDescription = $customDescription;

        return $this;
    }

    public function getCustomBookingInformation(): string
    {
        return $this->customBookingInformation;
    }

    public function setCustomBookingInformation(string $customBookingInformation): self
    {
        $this->customBookingInformation = $customBookingInformation;

        return $this;
    }

    public function isUseCustomInformation(): bool
    {
        return $this->isUseCustomInformation;
    }

    public function setIsUseCustomInformation(bool $isUseCustomInformation): self
    {
        $this->isUseCustomInformation = $isUseCustomInformation;

        return $this;
    }

    public function getCustomDescriptionShort(): string
    {
        return $this->customDescriptionShort;
    }

    public function setCustomDescriptionShort(string $customDescriptionShort): self
    {
        $this->customDescriptionShort = $customDescriptionShort;

        return $this;
    }

    public function getCustomBookingInformationShort(): string
    {
        return $this->customBookingInformationShort;
    }

    public function setCustomBookingInformationShort(string $customBookingInformationShort): self
    {
        $this->customBookingInformationShort = $customBookingInformationShort;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isUseDynamicPricing(): bool
    {
        return $this->isUseDynamicPricing;
    }

    public function setIsUseDynamicPricing(bool $isUseDynamicPricing): self
    {
        $this->isUseDynamicPricing = $isUseDynamicPricing;

        return $this;
    }

    public function getBookingType(): int
    {
        return $this->bookingType;
    }

    public function setBookingType(int $bookingType): self
    {
        $this->bookingType = $bookingType;

        return $this;
    }

    public function getTeeTimeSource(): string
    {
        return $this->teeTimeSource;
    }

    public function setTeeTimeSource(?string $teeTimeSource): self
    {
        $this->teeTimeSource = $teeTimeSource ?? self::SOURCE_NONE;

        return $this;
    }

    public function getMembershipCourses(): Collection
    {
        return $this->membershipCourses;
    }

    public function addMembershipGolfCourse(MembershipCourse $membershipGolfCourse): self
    {
        if (false === $this->getMembershipCourses()->contains($membershipGolfCourse)) {
            $this->getMembershipCourses()->add($membershipGolfCourse);
            $membershipGolfCourse->setCourse($this);
        }

        return $this;
    }

    public function getPeriods(): Collection
    {
        return $this->periods;
    }

    public function addPeriod(Period $period): self
    {
        if (false === $this->getPeriods()->contains($period)) {
            $this->getPeriods()->add($period);
            $period->setCourse($this);
        }

        return $this;
    }

    public function getTeeTimes(): Collection
    {
        return $this->teeTimes;
    }

    public function addTeeTime(TeeTime $teeTime): self
    {
        if (false === $this->getTeeTimes()->contains($teeTime)) {
            $this->getTeeTimes()->add($teeTime);
            $teeTime->setCourse($this);
        }

        return $this;
    }

    public function getPricePeriods()
    {
        return $this->pricePeriods;
    }

    public function addPricePeriod(PricePeriod $pricePeriod): self
    {
        if (false === $this->getPricePeriods()->contains($pricePeriod)) {
            $this->getPricePeriods()->add($pricePeriod);
            $pricePeriod->setCourse($this);
        }

        return $this;
    }

    public function getTeeTimeBookings()
    {
        return $this->teeTimeBookings;
    }

    public function addTeeTimeBooking(TeeTimeBooking $booking): self
    {
        if (false === $this->getTeeTimeBookings()->contains($booking)) {
            $this->getTeeTimeBookings()->add($booking);
            $booking->setCourse($this);
        }

        return $this;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (false === $this->players->contains($player)) {
            $this->players->add($player);
            $player->addFavoriteGolfCourse($this);
        }

        return $this;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = round($distance, self::PRECISION);

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImage(CourseImage $golfCourseImage): self
    {
        if (false === $this->images->contains($golfCourseImage)) {
            $this->images->add($golfCourseImage);
            $golfCourseImage->setCourse($this);
        }

        return $this;
    }

    public function isCanPay(): bool
    {
        return $this->isCanPay;
    }

    public function setIsCanPay(bool $isCanPay): self
    {
        $this->isCanPay = $isCanPay;

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

    public function getJuniorDiscount(): ?int
    {
        return $this->juniorDiscount;
    }

    public function setJuniorDiscount(?int $juniorDiscount): self
    {
        $this->juniorDiscount = $juniorDiscount;

        return $this;
    }

    public function isGitActive(): bool
    {
        return self::SOURCE_GIT === $this->teeTimeSource && null !== $this->gitId && $this->isActive;
    }

    public function isSweetspotActive(): bool
    {
        return self::SOURCE_SWEETSPOT === $this->teeTimeSource && $this->isActive;
    }

    public function getDisplayTeeTimeDays(): ?int
    {
        return $this->displayTeeTimeDays;
    }

    public function setDisplayTeeTimeDays(?int $displayTeeTimeDays): self
    {
        $this->displayTeeTimeDays = $displayTeeTimeDays;

        return $this;
    }

    public function getTeeSheetLinks(): Collection
    {
        return $this->teeSheetLinks;
    }

    public function addTeeSheetLink(TeeSheetLink $teeSheetLink): self
    {
        if (false === $this->getTeeSheetLinks()->contains($teeSheetLink)) {
            $this->getTeeSheetLinks()->add($teeSheetLink);
            $teeSheetLink->setCourse($this);
        }

        return $this;
    }

    public function isUseBookingConfirmationExpiration(): bool
    {
        return $this->isUseBookingConfirmationExpiration;
    }

    public function setIsUseBookingConfirmationExpiration(bool $isUseBookingConfirmationExpiration): self
    {
        $this->isUseBookingConfirmationExpiration = $isUseBookingConfirmationExpiration;

        return $this;
    }

    public function getGuide(): CourseGuide
    {
        return $this->guide;
    }

    public function setGuide(CourseGuide $guide): self
    {
        $this->guide = $guide;

        return $this;
    }

    public function isStubPlayersEnabled(): bool
    {
        return $this->isStubPlayersEnabled;
    }

    public function setIsStubPlayersEnabled(bool $isStubPlayersEnabled): self
    {
        $this->isStubPlayersEnabled = $isStubPlayersEnabled;

        return $this;
    }

    public function getBookedFromAppDiscount(): int
    {
        return $this->bookedFromAppDiscount;
    }

    public function setBookedFromAppDiscount(int $bookedFromAppDiscount): Course
    {
        $this->bookedFromAppDiscount = $bookedFromAppDiscount;

        return $this;
    }

    public function isPayOnSiteEnabled(): bool
    {
        return $this->isPayOnSiteEnabled;
    }

    public function setIsPayOnSiteEnabled(bool $isPayOnSiteEnabled): Course
    {
        $this->isPayOnSiteEnabled = $isPayOnSiteEnabled;

        return $this;
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }

    public function setSearchField(string $searchField): Course
    {
        $this->searchField = $searchField;

        return $this;
    }

    public function getCaddeeId(): ?string
    {
        return $this->caddeeId;
    }

    public function setCaddeeId(?string $caddeeId): Course
    {
        $this->caddeeId = $caddeeId;

        return $this;
    }

    public function isArrivalRegistrationAfterSchedule(): bool
    {
        return $this->isArrivalRegistrationAfterSchedule;
    }

    public function setIsArrivalRegistrationAfterSchedule(bool $isArrivalRegistrationAfterSchedule): Course
    {
        $this->isArrivalRegistrationAfterSchedule = $isArrivalRegistrationAfterSchedule;

        return $this;
    }
}
