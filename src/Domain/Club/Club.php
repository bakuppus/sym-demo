<?php

declare(strict_types=1);

namespace App\Domain\Club;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Domain\Club\Embeddable\Billing;
use App\Domain\Club\Embeddable\Schedule;
use App\Domain\Player\PlayRight;
use App\Domain\Player\PlayRightImport;
use App\Domain\Promotion\MembershipCard;
use App\Domain\Membership\PlayerMembershipToAssign;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Domain\Admin\Admin;
use App\Domain\Communication\HomeClubPost;
use App\Domain\Communication\HomeClubSetting;
use App\Domain\Course\Course;
use App\Domain\Player\PlayerFact;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Type\Spatial\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Application\Command\Club\Crm\CreateClub\CreateClubCommand;
use App\Application\Command\Club\Crm\UpdateClub\UpdateClubCommand;
use App\Application\Command\Club\Crm\AddNewCourse\AddNewCourseCommand;
use App\Application\Command\Club\Crm\AddNewMembership\AddNewMembershipCommand;

/**
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "path"="/crm/clubs",
 *              "input"=CreateClubCommand::class,
 *              "normalization_context"={"groups"={"Default", "create_club"}},
 *              "denormalization_context"={"groups"={"Default", "create_club"}}
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "path"="/crm/clubs/{id}",
 *              "input"=UpdateClubCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_club"}},
 *              "denormalization_context"={"groups"={"Default", "update_club"}}
 *          },
 *          "delete"={"path"="/crm/clubs/{id}"},
 *          "add_new_course"={
 *              "method"="PUT",
 *              "path"="/crm/clubs/{id}/courses/new",
 *              "input"=AddNewCourseCommand::class,
 *              "normalization_context"={"groups"={"Default", "add_new_course"}},
 *              "denormalization_context"={"groups"={"Default", "add_new_course"}},
 *              "swagger_context"={
 *                  "summary"="Add new course"
 *              }
 *          },
 *          "add_new_membership"={
 *              "method"="PUT",
 *              "path"="/crm/clubs/{id}/memberships/new",
 *              "input"=AddNewMembershipCommand::class,
 *              "validation_groups"={"add_new_membership"},
 *              "normalization_context"={"groups"={"Default", "add_new_membership"}},
 *              "denormalization_context"={"groups"={"Default", "add_new_membership"}},
 *              "swagger_context"={
 *                  "summary"="Add new membership"
 *              }
 *          }
 *     },
 *     normalizationContext={"groups"={"Default", "get_club", "list_club"}}
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="golf_clubs")
 */
class Club implements DeleteCommandAwareInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const LOCALE_CODE = 'SE';

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=150)
     *
     * @Groups({"list_course", "player_list"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gitId;

    /**
     * @var Point|null
     *
     * @Groups({"get_club", "list_club"})
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
     *
     * @ORM\Column(type="point", nullable=true)
     */
    private $lonlat;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=21)
     */
    private $phone;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=150)
     */
    private $website;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=250)
     */
    private $descriptionShort;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=1000)
     */
    private $bookingInformation;

    /**
     * @var string
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="string", length=250)
     */
    private $bookingInformationShort;

    /**
     * @var bool
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isSyncWithGit;

    /**
     * @var bool
     *
     * @Groups({"get_club", "list_club"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isAdminAssureBookable;

    /**
     * @var Course[]|Collection
     *
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Domain\Course\Course", mappedBy="club")
     */
    private $courses;

    /**
     * @var Membership[]|Collection
     *
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\Membership", mappedBy="club")
     */
    private $memberships;

    /**
     * @var ClubImage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Club\ClubImage", mappedBy="club", cascade={"persist"})
     */
    private $images;

    /**
     * @var MembershipCard[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\MembershipCard", mappedBy="club")
     */
    private $membershipCards;

    /**
     * @var Admin[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Admin\Admin", mappedBy="clubs")
     */
    private $admins;

    /**
     * @var ClubPartnerType[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Club\ClubPartnerType", mappedBy="club", cascade={"persist"})
     */
    private $partnerTypes;

    /**
     * @var Club[]|Collection|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Club\Club", mappedBy="partners")
     */
    private $partnersWithMe;

    /**
     * @var Club[]|Collection|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Club\Club", inversedBy="partnersWithMe")
     * @ORM\JoinTable(
     *     name="golf_club_partners",
     *     joinColumns={@ORM\JoinColumn(name="golf_club_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="partner_club_id", referencedColumnName="id")}
     * )
     */
    private $partners;

    /**
     * @var PlayerFact[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayerFact", mappedBy="club")
     */
    private $facts;

    /**
     * @var HomeClubSetting|null
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Communication\HomeClubSetting", mappedBy="club")
     */
    private $homeClubSetting;

    /**
     * @var HomeClubPost[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Communication\HomeClubPost", mappedBy="club")
     */
    private $homeClubPosts;

    /**
     * @var Schedule
     *
     * @ORM\Embedded(class="App\Domain\Club\Embeddable\Schedule")
     */
    private $schedule;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isScoreCardPrintingEnabled = false;

    /**
     * @var PlayRight[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayRight", mappedBy="golfClub", fetch="EXTRA_LAZY")
     */
    protected $playRights;

    /**
     * @var PlayRightImport[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Player\PlayRightImport", mappedBy="golfClub", fetch="EXTRA_LAZY")
     */
    protected $playRightImports;

    /**
     * @var PlayerMembershipToAssign[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\PlayerMembershipToAssign", mappedBy="golfClub")
     */
    private $playerMembershipToAssign;

    /**
     * @var Billing
     *
     * @ORM\Embedded(class="App\Domain\Club\Embeddable\Billing")
     */
    private $billing;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->memberships = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->membershipCards = new ArrayCollection();
        $this->partnerTypes = new ArrayCollection();
        $this->partnersWithMe = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->facts = new ArrayCollection();
        $this->schedule = new Schedule();
        $this->billing = new Billing();
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

    public function getGitId(): ?string
    {
        return $this->gitId;
    }

    public function setGitId(?string $gitId): self
    {
        $this->gitId = $gitId;

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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

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

    public function getDescriptionShort(): string
    {
        return $this->descriptionShort;
    }

    public function setDescriptionShort(string $descriptionShort): self
    {
        $this->descriptionShort = $descriptionShort;

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

    public function getBookingInformationShort(): string
    {
        return $this->bookingInformationShort;
    }

    public function setBookingInformationShort(string $bookingInformationShort): self
    {
        $this->bookingInformationShort = $bookingInformationShort;

        return $this;
    }

    public function isSyncWithGit(): bool
    {
        return $this->isSyncWithGit;
    }

    public function setIsSyncWithGit(bool $isSyncWithGit): self
    {
        $this->isSyncWithGit = $isSyncWithGit;

        return $this;
    }

    public function isAdminAssureBookable(): bool
    {
        return $this->isAdminAssureBookable;
    }

    public function setIsAdminAssureBookable(bool $isAdminAssureBookable): self
    {
        $this->isAdminAssureBookable = $isAdminAssureBookable;

        return $this;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (false === $this->getCourses()->contains($course)) {
            $this->getCourses()->add($course);
            $course->setClub($this);
        }

        return $this;
    }

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(Membership $membership): self
    {
        if (false === $this->getMemberships()->contains($membership)) {
            $this->getMemberships()->add($membership);
            $membership->setClub($this);
        }

        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImage(ClubImage $ClubImage): self
    {
        if (false === $this->images->contains($ClubImage)) {
            $this->images->add($ClubImage);
            $ClubImage->setClub($this);
        }

        return $this;
    }

    public function getMembershipCards(): Collection
    {
        return $this->membershipCards;
    }

    public function getPartnerTypes(): Collection
    {
        return $this->partnerTypes;
    }

    public function setPartnerType(ClubPartnerType $partnerType): self
    {
        if (false === $this->partnerTypes->contains($partnerType)) {
            $this->partnerTypes->add($partnerType);
            $partnerType->setClub($this);
        }

        return $this;
    }

    public function getPartnersWithMe(): Collection
    {
        return $this->partnersWithMe;
    }

    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Club $partner): self
    {
        if (false === $this->partners->contains($partner)) {
            $this->partners->add($partner);
            $partner->addPartner($this);
        }

        return $this;
    }

    public function removePartner(Club $partner): self
    {
        if (true === $this->partners->contains($partner)) {
            $this->partners->removeElement($partner);
            $partner->removePartner($this);
        }

        return $this;
    }

    public function getFirstPartner(): ?Club
    {
        $partner = $this->partners->first();

        if (false === $partner) {
            return null;
        }

        return $partner;
    }

    public function getFacts(): Collection
    {
        return $this->facts;
    }

    public function setFact(PlayerFact $fact): self
    {
        if (false === $this->facts->contains($fact)) {
            $this->facts->add($fact);
            $fact->setClub($this);
        }

        return $this;
    }

    public function getHomeClubSetting(): ?HomeClubSetting
    {
        return $this->homeClubSetting;
    }

    public function setHomeClubSetting(?HomeClubSetting $homeClubSetting): self
    {
        $this->homeClubSetting = $homeClubSetting;

        return $this;
    }

    /**
     * @return HomeClubPost[]|Collection
     */
    public function getHomeClubPosts(): Collection
    {
        return $this->homeClubPosts;
    }

    public function setHomeClubPosts($homeClubPosts): self
    {
        $this->homeClubPosts = $homeClubPosts;

        return $this;
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function isScoreCardPrintingEnabled(): bool
    {
        return $this->isScoreCardPrintingEnabled;
    }

    public function setIsScoreCardPrintingEnabled(bool $isScoreCardPrintingEnabled): Club
    {
        $this->isScoreCardPrintingEnabled = $isScoreCardPrintingEnabled;

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
            $this->playRights->add($playRight);
            $playRight->setGolfClub($this);
        }

        return $this;
    }

    /**
     * @return Collection|PlayRightImport[]
     */
    public function getPlayRightImports(): Collection
    {
        return $this->playRightImports;
    }

    /**
     * @param PlayRightImport $playRightImport
     *
     * @return $this
     */
    public function addPlayRightImport(PlayRightImport $playRightImport): self
    {
        if (false === $this->playRightImports->contains($playRightImport)) {
            $this->playRightImports->add($playRightImport);
            $playRightImport->setGolfClub($this);
        }

        return $this;
    }

    public function getBilling(): Billing
    {
        return $this->billing;
    }

    public function setBilling(Billing $billing): void
    {
        $this->billing = $billing;
    }
}
