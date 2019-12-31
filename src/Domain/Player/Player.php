<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Booking\TeeTimeBookingRating;
use App\Domain\Booking\TeeTimeBookingReview;
use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Domain\Order\Core\OrderInterface;
use App\Domain\Membership\PlayerMembershipToAssign;
use App\Domain\Order\Order;
use App\Domain\Payment\CreditCard;
use App\Domain\Promotion\MembershipCard;
use App\Domain\Shared\Role;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerRelatedGolfClubFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerSearchFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerTypeFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerMembershipFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerMembershipPaidFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerMembershipGitOnlyFilter;
use App\Infrastructure\Elasticsearch\DataProvider\Filter\Player\PlayerExternalFilter;

/**
 * TODO: Player needs to be refactored @see https://sweetspot-io.slack.com/archives/CKTH9E692/p1561388033027500
 * TODO: Player should implement SF authentication model instead of Laravel.
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"Default", "player_list"}}
 * )
 *
 * @ORM\Entity
 * @Gedmo\SoftDeleteable
 * @Gedmo\Loggable(logEntryClass="PlayerLogEntry")
 *
 * @ApiFilter(PlayerRelatedGolfClubFilter::class, properties={"relatedGolfClub"})
 * @ApiFilter(PlayerSearchFilter::class, properties={"search"})
 * @ApiFilter(PlayerTypeFilter::class, properties={"type"})
 * @ApiFilter(PlayerMembershipFilter::class, properties={"membership"})
 * @ApiFilter(PlayerMembershipPaidFilter::class, properties={"membershipPaid"})
 * @ApiFilter(PlayerMembershipGitOnlyFilter::class, properties={"gitOnlyMembership"})
 * @ApiFilter(PlayerExternalFilter::class, properties={"externalPlayer"})
 */
class Player
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /*use AuthenticatableTrait;
    use StringFormatterTrait;
    use HasRoles;*/

    // TODO: Should be refactored, DDD principle violation. Domain shouldn't know about Application
    /*public const JWT_GUARD = AuthService::API_PLAYER_GUARD;*/

    public const GENDER_MAN = 'man';
    public const GENDER_WOMAN = 'woman';

    public const CREATION_SOURCE_SS_BOOKING = 'ss_booking';
    public const CREATION_SOURCE_GIT_IMPORT_API = 'git_import_api';
    public const CREATION_SOURCE_GIT_IMPORT_CSV = 'git_import_csv';
    public const CREATION_SOURCE_GIT_API = 'git_api';
    public const CREATION_SOURCE_REGISTERED = 'ss_registered';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remember_token", type="string", nullable=true)
     */
    private $rememberToken;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string", nullable=true, unique=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $firstName = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $lastName = null;

    /**
     * @var string
     *
     * @Groups({"get_order_membership"})
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true, unique=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $phone = null;

    /**
     * @var string|null
     *
     * @Groups({"get_order_membership"})
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true, unique=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $golfId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gitId;

    /**
     * @var Club|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", cascade={"persist"})
     *
     * @Groups({"player_list"})
     */
    private $homeClub;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"player_list"})
     */
    private $isActiveMembership = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $hcp;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $brainTreeId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $payPalEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $cardBrand;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $cardLastFour;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"player_list"})
     */
    private $trialEndsAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     *
     * @Groups({"player_list"})
     */
    private $favoriteCourses;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", unique=true, nullable=true)
     *
     * @Groups({"player_list"})
     */
    private $fuid;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Groups({"player_list"})
     */
    private $isFireBaseAuthorized = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Groups({"player_list"})
     */
    private $isRegistered = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"player_list"})
     */
    private $isCreatedByMembersImport = false;

    /**
     * @var MembershipCard[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\MembershipCard", mappedBy="player")
     */
    private $membershipCards;

    /**
     * @deprecated
     *
     * @var MembershipCard|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\MembershipCard")
     */
    private $personalMembership;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     *
     * @Groups({"player_list"})
     */
    private $isCanBook = true;

    /**
     * @deprecated
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isCreatedBySearchThroughGitApi = false;

    /**
     * @var TeeTimeBookingParticipant[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Booking\TeeTimeBookingParticipant", mappedBy="player",
     *     cascade={"persist"})
     */
    private $bookingParticipants;

    /**
     * @var Course[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Course\Course", inversedBy="players")
     * @ORM\JoinTable(name="players_favorite_courses",
     *      joinColumns={@ORM\JoinColumn(name="player_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="golf_course_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $favoriteGolfCourses;

    /**
     * @var TeeTimeBooking[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Booking\TeeTimeBooking", mappedBy="owner")
     */
    private $teeTimeBookings;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $hash;

    /**
     * @var Role[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Role", inversedBy="players")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     *
     * @Groups({"player_list"})
     */
    private $language; /* = UserInterface::LANG_SWEDISH;*/

    /**
     * @var PlayerMobileDevice[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerMobileDevice", mappedBy="player", cascade={"persist"})
     */
    private $mobileDevices;

    /**
     * @var PlayerPaymentMethod[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerPaymentMethod", mappedBy="player", cascade={"persist"})
     */
    private $paymentMethods;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=25)
     *
     * @Groups({"player_list"})
     */
    private $gender;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @Groups({"player_list"})
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $creationSource;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $gitEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $gitPhone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $gitFirstName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $gitLastName;

    /**
     * @var Player|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="mergedWith")
     * @ORM\JoinColumn(name="merged_to_id", referencedColumnName="id")
     */
    private $mergedTo;

    /**
     * @var Collection|Player[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\Player", mappedBy="mergedTo")
     */
    private $mergedWith;

    /**
     * @var PlayerGolfFriend[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerGolfFriend", mappedBy="player")
     */
    private $golfFriends;

    /**
     * @var PlayerFact[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerFact", mappedBy="player", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     *
     * @Groups({"player_list"})
     */
    private $facts;

    /**
     * @var TeeTimeBookingRating[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Booking\TeeTimeBookingRating", mappedBy="player", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    protected $ratings;

    /**
     * @var TeeTimeBookingReview[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Booking\TeeTimeBookingReview", mappedBy="player", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    protected $reviews;

    /**
     * @var Playright[]|Collection
     *
     * @ORM\OneToMany(targetEntity="PlayRight", mappedBy="player", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    protected $playRights;

    /**
     * @var bool|null
     *
     * @Groups({"player_list"})
     */
    private $isMembershipPaid;

    /**
     * @var PlayerMembershipToAssign[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Membership\PlayerMembershipToAssign",
     *     cascade={"persist"},
     *     mappedBy="player"
     * )
     */
    private $playerMembershipToAssign;

    /**
     * @var PlayerMembershipToAssign
     *
     * @Groups({"player_list"})
     */
    private $showPlayerMembershipToAssign;

    /**
     * @var bool
     *
     * @Groups({"player_list"})
     */
    private $isGitMember;

    /**
     * @var bool
     *
     * @Groups({"player_list"})
     */
    private $hasPlayRight;

    /**
     * @var PlayerCourseInterest[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerCourseInterest", mappedBy="player", fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"})
     */
    protected $golfCourseInterests;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $address;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $zipCode;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $country;

    /**
     * @var Order[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Order\Order", mappedBy="customer")
     */
    private $orders;

    /**
     * @var CreditCard[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Payment\CreditCard", mappedBy="customer")
     */
    private $creditCards;

    /**
     * @var MembershipCard[]
     *
     * @Groups({"player_list"})
     */
    private $shownMemberships = [];

    /**
     * @var null|MembershipCard
     *
     * @Groups({"player_list"})
     */
    private $shownOneMembership;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        /*$this->initializePasswordCrypter();*/

        $this->uuid = Uuid::uuid4();

        $this->membershipCards = new ArrayCollection();
        $this->bookingParticipants = new ArrayCollection();
        $this->favoriteGolfCourses = new ArrayCollection();
        $this->teeTimeBookings = new ArrayCollection();
        /*$this->roles = new ArrayCollection();*/
        $this->paymentMethods = new ArrayCollection();
        $this->mobileDevices = new ArrayCollection();
        $this->mergedWith = new ArrayCollection();
        $this->golfFriends = new ArrayCollection();
        $this->facts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->playRights = new ArrayCollection();
        $this->playerMembershipToAssign = new ArrayCollection();
    }

    public function __clone()
    {
        $this->uuid = null;
        $this->fuid = null;
        $this->id = null;
    }

    public function getEmail(): string
    {
        return (string)$this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): string
    {
        return (string)$this->phone;
    }

    public function setPhone(?string $phone): self
    {
        /* TODO: $this->phone = $phone ? $this->formatPhoneNumber($phone) : null;*/

        return $this;
    }

    public function getGolfId(): ?string
    {
        return $this->golfId;
    }

    public function setGolfId(?string $golfId): self
    {
        $this->golfId = $golfId;

        return $this;
    }

    public function getGitId(): ?string
    {
        return $this->gitId;
    }

    public function isGitLogged(): bool
    {
        return (bool)$this->gitId && (bool)$this->golfId;
    }

    public function setGitId(?string $gitId): Player
    {
        $this->gitId = $gitId;

        return $this;
    }

    public function getHomeClub(): ?Club
    {
        return $this->homeClub;
    }

    public function setHomeClub(?Club $homeClub): self
    {
        $this->homeClub = $homeClub;

        return $this;
    }

    public function getIsActiveMembership(): bool
    {
        return (bool)$this->isActiveMembership;
    }

    public function setIsActiveMembership(bool $isActiveMembership): self
    {
        $this->isActiveMembership = $isActiveMembership;

        return $this;
    }

    public function getHcp(): ?string
    {
        return $this->hcp;
    }

    public function setHcp(?string $hcp): self
    {
        // From GIT we have negative value. I don't know why
        if (null !== $hcp && 0 === strpos($hcp, '-')) {
            $hcp = substr($hcp, 1);
        }
        $this->hcp = str_replace(',', '.', $hcp);

        return $this;
    }

    public function getBrainTreeId(): ?string
    {
        return $this->brainTreeId;
    }

    public function setBrainTreeId(?string $brainTreeId): self
    {
        $this->brainTreeId = $brainTreeId;

        return $this;
    }

    public function getPayPalEmail(): ?string
    {
        return $this->payPalEmail;
    }

    public function setPayPalEmail(?string $payPalEmail): self
    {
        $this->payPalEmail = $payPalEmail;

        return $this;
    }

    public function getCardBrand(): ?string
    {
        return $this->cardBrand;
    }

    public function setCardBrand(?string $cardBrand): self
    {
        $this->cardBrand = $cardBrand;

        return $this;
    }

    public function getCardLastFour(): ?string
    {
        return $this->cardLastFour;
    }

    public function setCardLastFour(?string $cardLastFour): self
    {
        $this->cardLastFour = $cardLastFour;

        return $this;
    }

    public function getTrialEndsAt(): ?DateTime
    {
        return $this->trialEndsAt;
    }

    public function setTrialEndsAt(?DateTime $trialEndsAt): self
    {
        $this->trialEndsAt = $trialEndsAt;

        return $this;
    }

    public function getFavoriteCourses(): ?string
    {
        return $this->favoriteCourses;
    }

    public function setFavoriteCourses(?string $favoriteCourses): self
    {
        $this->favoriteCourses = $favoriteCourses;

        return $this;
    }

    public function getFuid(): ?string
    {
        return $this->fuid;
    }

    public function setFuid(?string $fuid): self
    {
        $this->fuid = $fuid;

        return $this;
    }

    public function getIsFireBaseAuthorized(): bool
    {
        return $this->isFireBaseAuthorized ?: false;
    }

    public function setIsFireBaseAuthorized(bool $isFireBaseAuthorized = true): self
    {
        $this->isFireBaseAuthorized = $isFireBaseAuthorized;

        return $this;
    }

    public function getIsRegistered(): bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(bool $isRegistered = true): self
    {
        $this->isRegistered = $isRegistered;

        return $this;
    }

    public function isCreatedByMembersImport(): bool
    {
        return $this->isCreatedByMembersImport;
    }

    /**
     * @deprecated
     */
    public function setIsCreatedByMembersImport(bool $isCreatedByMembersImport = false): self
    {
        $this->isCreatedByMembersImport = $isCreatedByMembersImport;

        return $this;
    }

    public function getMembershipCards(): Collection
    {
        return $this->membershipCards;
    }

    public function addGroupMembership(MembershipCard $membership): self
    {
        if (false === $this->membershipCards->contains($membership)) {
            $this->membershipCards->add($membership);
            $membership->setPlayer($this);
        }

        return $this;
    }

    public function getPersonalMembership(): ?MembershipCard
    {
        return $this->personalMembership;
    }

    public function setPersonalMembership(?MembershipCard $personalMembership): self
    {
        $this->personalMembership = $personalMembership;

        return $this;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getId();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
//    public function getJWTCustomClaims()
//    {
//        return ['guard' => $this->getJWTGuard()];
//    }

//    public function getJWTGuard(): string
//    {
//        return self::JWT_GUARD;
//    }

    public function getIsCanBook(): bool
    {
        return $this->isCanBook;
    }

    public function setIsCanBook(bool $isCanBook): self
    {
        $this->isCanBook = $isCanBook;

        return $this;
    }

    /**
     * @deprecated
     */
    public function isCreatedBySearchThroughGitApi(): bool
    {
        return $this->isCreatedBySearchThroughGitApi;
    }

    /**
     * @deprecated
     */
    public function setIsCreatedBySearchThroughGitApi(bool $isCreatedBySearchThroughGitApi): self
    {
        $this->isCreatedBySearchThroughGitApi = $isCreatedBySearchThroughGitApi;

        return $this;
    }

    public function getBookingParticipants()
    {
        return $this->bookingParticipants;
    }

    public function addBookingParticipant(TeeTimeBookingParticipant $participant): self
    {
        if (false === $this->getBookingParticipants()->contains($participant)) {
            $this->getBookingParticipants()->add($participant);
            $participant->setPlayer($this);
        }

        return $this;
    }

    /**
     * @return Course[]|Collection
     */
    public function getFavoriteGolfCourses()
    {
        return $this->favoriteGolfCourses;
    }

    public function addFavoriteGolfCourse(Course $golfCourse): self
    {
        if (false === $this->favoriteGolfCourses->contains($golfCourse)) {
            $this->favoriteGolfCourses->add($golfCourse);
            $golfCourse->addPlayer($this);
        }

        return $this;
    }

    /**
     * @return TeeTimeBooking[]|Collection
     */
    public function getTeeTimeBookings(): Collection
    {
        return $this->teeTimeBookings;
    }

    public function addTeeTimeBooking(TeeTimeBooking $booking): self
    {
        if (false === $this->teeTimeBookings->contains($booking)) {
            $this->teeTimeBookings->add($booking);
            $booking->setOwner($this);
        }

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

//    public function getRoles(): Collection
//    {
//        return $this->roles;
//    }

//    public function addRole(Role $role)
//    {
//        if (false === $this->roles->contains($role)) {
//            $this->roles->add($role);
//        }
//
//        return $this;
//    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @deprecated
     */
    public function isCreatedAsParticipant(): bool
    {
        return null === $this->golfId
            && null === $this->email
            && null !== $this->phone;
    }

    public function addPaymentMethod(PlayerPaymentMethod $paymentMethod): self
    {
        if (false === $this->paymentMethods->contains($paymentMethod)) {
            $this->paymentMethods->add($paymentMethod);
            $paymentMethod->setPlayer($this);
        }

        return $this;
    }

    /**
     * @return PlayerPaymentMethod[]|ArrayCollection|Collection
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    public function removePaymentMethod(PlayerPaymentMethod $paymentMethod): self
    {
        if (false === $this->paymentMethods->contains($paymentMethod)) {
            $this->paymentMethods->removeElement($paymentMethod);
            $paymentMethod->setPlayer(null);
        }

        return $this;
    }

    public function getUsername(): string
    {
        $class = __CLASS__;

        return "{$this->id}:{$class}";
    }

    /**
     * @Groups({"get_order_membership"})
     *
     * @return string
     */
    public function getFullName(): string
    {
        return trim("{$this->firstName} {$this->lastName}");
    }

    /**
     * @return PlayerMobileDevice[]|Collection
     */
    public function getMobileDevices(): Collection
    {
        return $this->mobileDevices;
    }

    public function addMobileDevice(PlayerMobileDevice $mobileDevice): self
    {
        if (false === $this->mobileDevices->contains($mobileDevice)) {
            $this->mobileDevices->add($mobileDevice);
            $mobileDevice->setPlayer($this);
        }

        return $this;
    }

    public function removeMobileDevice(PlayerMobileDevice $mobileDevice): self
    {
        if (true === $this->mobileDevices->contains($mobileDevice)) {
            $this->mobileDevices->removeElement($mobileDevice);
            $mobileDevice->setPlayer(null);
        }

        return null;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGitEmail(): ?string
    {
        return $this->gitEmail;
    }

    public function setGitEmail(?string $gitEmail): self
    {
        $this->gitEmail = $gitEmail;

        return $this;
    }

    public function getGitPhone(): ?string
    {
        return $this->gitPhone;
    }

    public function setGitPhone(?string $gitPhone): self
    {
        $this->gitPhone = $gitPhone;

        return $this;
    }

    public function getGitFirstName(): ?string
    {
        return $this->gitFirstName;
    }

    public function setGitFirstName(?string $gitFirstName): self
    {
        $this->gitFirstName = $gitFirstName;

        return $this;
    }

    public function getGitLastName(): ?string
    {
        return $this->gitLastName;
    }

    public function setGitLastName(?string $gitLastName): self
    {
        $this->gitLastName = $gitLastName;

        return $this;
    }

    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTime $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAge(): ?int
    {
        if (null === $this->birthday) {
            return null;
        }

        return Carbon::now()->diffInYears($this->getBirthday());
    }

    public function getCreationSource(): ?string
    {
        return $this->creationSource;
    }

    public function setCreationSource(?string $creationSource): self
    {
        $this->creationSource = $creationSource;

        return $this;
    }

    /**
     * @Groups({"get_order_membership"})
     *
     * @return string|null
     */
    public function getExistingEmail(): ?string
    {
        $email = $this->getEmail();
        $gitEmail = $this->getGitEmail();

        if ('' !== $email && null !== $email) {
            return trim($email);
        }

        if ('' !== $gitEmail && null !== $gitEmail) {
            return trim($gitEmail);
        }

        return null;
    }

    public function getMergedTo(): ?Player
    {
        return $this->mergedTo;
    }

    public function setMergedTo(?Player $mergedTo): self
    {
        $this->mergedTo = $mergedTo;

        return $this;
    }

    /**
     * @return Player[]|Collection
     */
    public function getMergedWith(): Collection
    {
        return $this->mergedWith;
    }

    public function isMerged(): bool
    {
        return null !== $this->mergedTo;
    }

    /**
     * @return Collection|PlayerGolfFriend[]
     */
    public function getGolfFriends(): Collection
    {
        return $this->golfFriends;
    }

    /**
     * @return Collection|PlayerFact[]
     */
    public function getFacts(): Collection
    {
        return $this->facts;
    }

    public function setFact(PlayerFact $fact): self
    {
        if (false === $this->facts->contains($fact)) {
            $this->facts->add($fact);
            $fact->setPlayer($this);
        }

        return $this;
    }

    public function getFact(): ?PlayerFact
    {
        return true === $this->facts->isEmpty() ? null : $this->facts->first();
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(TeeTimeBookingRating $rating): self
    {
        if (false === $this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setPlayer($this);
        }

        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(OrderInterface $order): self
    {
        if (false === $this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
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
            $review->setPlayer($this);
        }

        return $this;
    }

    /**
     * @return Collection|PlayRight[]
     */
    public function getPlayRights(): Collection
    {
        return $this->playRights;
    }

    /**
     * @param PlayRight $playRight
     *
     * @return $this
     */
    public function addPlayRight(PlayRight $playRight): self
    {
        if (false === $this->playRights->contains($playRight)) {
            $this->facts->add($playRight);
            $playRight->setPlayer($this);
        }

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }

    public function getIsMembershipPaid(): ?bool
    {
        return $this->isMembershipPaid;
    }

    public function setIsMembershipPaid(bool $isMembershipPaid): void
    {
        $this->isMembershipPaid = $isMembershipPaid;
    }

    public function isMembershipPaid(int $clubId): ?bool
    {
        $cards = $this->getMembershipCards()->filter(function (MembershipCard $card) use ($clubId) {
            return $clubId === $card->getClub()->getId();
        });

        if (true === $cards->isEmpty()) {
            return null;
        }

        $paidCards = $cards->filter(function (MembershipCard $card) use ($clubId) {
            return MembershipCard::STATE_PAID === $card->getState();
        });

        $isPaid = false === $paidCards->isEmpty();
        $this->setIsMembershipPaid($isPaid);

        return $isPaid;
    }

    /**
     * @return Collection|PlayerMembershipToAssign
     */
    public function getPlayerMembershipToAssign(): Collection
    {
        return $this->playerMembershipToAssign;
    }

    public function getIsGitMember(): ?bool
    {
        return $this->isGitMember;
    }

    public function setIsGitMember(bool $isGitMember): void
    {
        $this->isGitMember = $isGitMember;
    }

    public function isGitMember(int $clubId): bool
    {
        $memberships = $this->getPlayerMembershipToAssign()->filter(
            function (PlayerMembershipToAssign $toAssign) use ($clubId) {
                return $toAssign->getGolfClub()->getId() === $clubId;
            }
        );

        $isGitMember = false === $memberships->isEmpty();
        $this->setIsGitMember($isGitMember);

        return $isGitMember;
    }

    public function getHasPlayRight(): ?bool
    {
        return $this->hasPlayRight;
    }

    public function setHasPlayRight(bool $hasPlayRight): void
    {
        $this->hasPlayRight = $hasPlayRight;
    }

    public function hasPlayRight(int $clubId): bool
    {
        $playRights = $this->getPlayRights()->filter(function (PlayRight $playRight) use ($clubId) {
            return $playRight->getGolfClub()->getId() === $clubId;
        });

        $hasPlayRight = false === $playRights->isEmpty();
        $this->setHasPlayRight($hasPlayRight);

        return $hasPlayRight;
    }

    /**
     * @return MembershipCard[]
     */
    public function getShownMemberships(): array
    {
        return $this->shownMemberships;
    }

    public function setShownMemberships(array $shownMemberships): void
    {
        $this->shownMemberships = $shownMemberships;
    }

    /**
     * @param int $golfClubId
     *
     * @return Collection|MembershipCard[]
     */
    public function shownMemberships(int $golfClubId): array
    {
        $memberships = $this->getMembershipCards()->filter(function (MembershipCard $membershipCard) use ($golfClubId) {
            return MembershipCard::STATUS_OLD !== $membershipCard->getStatus()
                && $golfClubId === $membershipCard->getClub()->getId();
        });

        $memberships = $memberships->getValues();

        $this->setShownMemberships($memberships);

        return $memberships;
    }

    public function getShownOneMembership(): ?MembershipCard
    {
        return $this->shownOneMembership;
    }

    public function setShownOneMembership(?MembershipCard $membershipCard): void
    {
        $this->shownOneMembership = $membershipCard;
    }

    public function shownOneMembership(int $golfClubId): ?MembershipCard
    {
        $priorityList = array_flip(array_reverse(MembershipCard::STATUS_PRIORITY_SHOW));
        $max = -1;
        $returnedMembershipCard = null;
        foreach ($this->shownMemberships($golfClubId) as $membershipCard) {
            if (false === isset($priorityList[$membershipCard->getStatus()])) {
                continue;
            }

            $priority = $priorityList[$membershipCard->getStatus()];
            if ($priority > $max) {
                $max = $priority;
                $returnedMembershipCard = $membershipCard;
            }
        }

        $this->setShownOneMembership($returnedMembershipCard);

        return $returnedMembershipCard;
    }

    public function isIndexable(): bool
    {
        return false === $this->isDeleted() && false === $this->isStubPlayer();
    }

    public function isStubPlayer(): bool
    {
        return null === $this->getGolfId()
            && true === empty($this->getPassword())
            && true === empty($this->getEmail())
            && false === $this->getIsRegistered();
    }

    public function getShowPlayerMembershipToAssign(): ?PlayerMembershipToAssign
    {
        return $this->showPlayerMembershipToAssign;
    }

    public function setShowPlayerMembershipToAssign(?PlayerMembershipToAssign $playerMembershipToAssign): void
    {
        $this->showPlayerMembershipToAssign = $playerMembershipToAssign;
    }

    public function showPlayerMembershipToAssign(int $golfClubId): ?PlayerMembershipToAssign
    {
        $playerMembershipToAssignCollection = $this->getPlayerMembershipToAssign()->filter(
            function (PlayerMembershipToAssign $playerMembershipToAssign) use ($golfClubId) {
                return $golfClubId === $playerMembershipToAssign->getGolfClub()->getId();
            }
        );

        $playerMembershipToAssign = null;
        if (false === $playerMembershipToAssignCollection->isEmpty()) {
            $playerMembershipToAssign = $playerMembershipToAssignCollection->first();
        }

        $this->setShowPlayerMembershipToAssign($playerMembershipToAssign);

        return $playerMembershipToAssign;
    }
}
