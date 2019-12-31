<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Club\Club;
use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Membership\MembershipCourse;
use App\Domain\Membership\MembershipCourseGroup;
use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Core\MembershipInterface;
use App\Domain\Promotion\Component\MembershipInterface as BaseMembershipInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Domain\Accounting\FeeInterface;
use App\Domain\Accounting\SubjectFeeInterface;
use App\Application\Command\Promotion\Crm\AddPromotionToMembership\AddPromotionToMembershipCommand;
use App\Application\Command\Promotion\Crm\AddNewMembershipFee\AddNewMembershipFeeCommand;
use App\Application\Command\Promotion\Crm\UpdateMembership\UpdateMembershipCommand;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\MatchFilter;
use App\Application\Command\Promotion\Crm\PublishMembership\PublishMembershipCommand;
use App\Application\Command\Promotion\Crm\AddCardToMembership\AddCardToMembershipCommand;
use App\Application\Command\Promotion\Crm\SyncMembership\SyncMembershipCommand;

/**
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="FK_WRHUZNAM51SPDF28", columns={"golf_club_id", "name"})})
 * @ORM\HasLifecycleCallbacks
 *
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"Default", "get_fee", "list_memberships"}},
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"Default", "get_fee", "list_memberships", "get_membership"}},
 *          },
 *          "add_promotion_to_membership"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}/promotion/new",
 *              "input"=AddPromotionToMembershipCommand::class,
 *              "normalization_context"={"groups"={"Default", "add_promotion_to_membership"}},
 *              "denormalization_context"={"groups"={"Default", "add_promotion_to_membership"}},
 *              "swagger_context"={
 *                  "summary"="Add promotion to membership"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "add_new_membership_fee"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}/fees/new",
 *              "input"=AddNewMembershipFeeCommand::class,
 *              "normalization_context"={"groups"={"Default", "create_fee"}},
 *              "denormalization_context"={"groups"={"Default", "create_fee"}},
 *              "swagger_context"={
 *                   "summary"="Add new membership fee"
 *              },
 *              "validation_groups"={"Default", "create_fee", "edit_membership"}
 *          },
 *          "update_membership"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}",
 *              "input"=UpdateMembershipCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_membership"}},
 *              "denormalization_context"={"groups"={"Default", "update_membership"}},
 *              "swagger_context"={
 *                  "summary"="Update membership"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "publish_membership"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}/publish",
 *              "input"=PublishMembershipCommand::class,
 *              "normalization_context"={"groups"={"Default", "get_membership", "get_fee"}},
 *              "denormalization_context"={"groups"={"Default"}},
 *              "swagger_context"={
 *                  "summary"="Add new membership fee"
 *              }
 *          },
 *          "add_card_to_membership"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}/card/new",
 *              "input"=AddCardToMembershipCommand::class,
 *              "normalization_context"={
 *                  "groups"={
 *                      "Default",
 *                      "add_card_to_membership",
 *                      "get_fee",
 *                      "list_memberships",
 *                      "get_membership",
 *                      "get_membership_card",
 *                      "get_club"
 *                  }
 *              },
 *              "denormalization_context"={"groups"={"Default", "add_card_to_membership"}},
 *              "swagger_context"={
 *                  "summary"="Add card to membership"
 *              },
 *              "validation_groups"={"Default", "add_card_to_membership"}
 *          },
 *          "sync_memebrship"={
 *              "method"="PUT",
 *              "path"="/crm/memberships/{id}/sync",
 *              "input"=SyncMembershipCommand::class,
 *              "denormalization_context"={"groups"={"Default", "sync_memebrship"}},
 *              "swagger_context"={
 *                  "summary"="Sync membership"
 *              }
 *          },
 *     },
 *     normalizationContext={"groups"={"Default", "list_memberships"}}
 * )
 * @ApiFilter(MatchFilter::class, properties={"club.id", "state", "name"})
 */
class Membership implements MembershipInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public const DURATION_ANNUAL = 'annual_duration';
    public const DURATION_12_MONTH = '12_month';

    public const GRAPH = 'membership';

    public const STATE_DRAFT = 'draft';
    public const STATE_PUBLISHED = 'published';
    public const STATE_OUTDATED = 'outdated';

    public const TRANSITION_PUBLISH = 'publish';
    public const TRANSITION_REJECT = 'reject';


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups({
     *      "list_memberships",
     *      "get_membership",
     *      "add_new_membership",
     *      "update_membership",
     *      "player_list"
     * })
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"list_memberships", "get_membership", "add_promotion_to_membership"})
     * @Gedmo\Slug(fields={"name"}, updatable=false, unique=true, unique_base="version")
     */
    private $code;

    /**
     * @var integer|null
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership"})
     */
    private $version = 1;

    /**
     * @var int
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $total = 0;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $durationOptions = [];

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $isActive = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $isGitSync = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $isHidden = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     */
    private $playRightOnly = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=false, options={"default"="draft"})
     *
     * @Groups({"list_memberships", "get_membership", "add_new_membership", "update_membership"})
     *
     * todo: add Assert\EqualTo when we will remove ability to edit published membership
     */
    private $state = self::STATE_DRAFT;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="memberships", cascade={"persist"})
     * @ORM\JoinColumn(name="golf_club_id")
     *
     * @Groups({"player_list"})
     */
    private $club;

    /**
     * @var Collection|PromotionInterface[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\Promotion", mappedBy="membership")
     *
     * @Groups({"get_membership"})
     */
    private $promotions;

    /**
     * @var Collection|MembershipCard[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\MembershipCard", mappedBy="membership")
     */
    private $membershipCards;

    /**
     * @var Collection|PromotionInterface[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\MembershipFee", mappedBy="membership")
     *
     * @Groups({"list_memberships", "get_membership", "get_fee"})
     */
    private $fees;

    /**
     * @deprecated
     *
     * @var float|null
     *
     * @ORM\Column(type="float", precision=6, length=20, nullable=true)
     */
    private $price;

    /**
     * @deprecated
     *
     * @var MembershipCourse[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\MembershipCourse", mappedBy="membership")
     */
    private $membershipCourses;

    /**
     * @deprecated
     *
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"default":0})
     */
    private $ownerDiscount;

    /**
     * @deprecated
     *
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"default":0})
     */
    private $friendDiscount;

    /**
     * @deprecated
     *
     * @var TeeTimeBookingParticipant[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Domain\Booking\TeeTimeBookingParticipant",
     *      mappedBy="ownerMembership",
     *      cascade={"persist"}
     * )
     */
    private $bookingParticipants;

    /**
     * @deprecated
     *
     * @var MembershipCourseGroup[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Domain\Membership\MembershipCourseGroup",
     *      mappedBy="membership",
     *      cascade={"persist", "remove"}
     * )
     */
    private $membershipCourseGroups;

    public function __construct()
    {
        $this->membershipCourses = new ArrayCollection();
        $this->bookingParticipants = new ArrayCollection();
        $this->membershipCourseGroups = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->membershipCards = new ArrayCollection();
        $this->fees = new ArrayCollection();
    }

    public function getName(): string
    {
        return (string)$this->name;
    }

    public function setName(string $name): BaseMembershipInterface
    {
        $this->name = $name;

        return $this;
    }

    public function setTotal(int $total): BaseMembershipInterface
    {
        $this->total = $total;

        return $this;
    }

    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->fees as $fee) {
            $total += $fee->getPrice();
        }

        $this->setTotal($total);

        return $this->total;
    }

    /**
     * @return Collection|PromotionInterface[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function hasPromotions(): bool
    {
        return false === $this->promotions->isEmpty();
    }

    public function hasPromotion(PromotionInterface $promotion): bool
    {
        return $this->promotions->contains($promotion);
    }

    public function addPromotion(PromotionInterface $promotion): MembershipInterface
    {
        if (false === $this->hasPromotion($promotion)) {
            $promotion->setMembership($this);
            $this->promotions->add($promotion);
        }

        return $this;
    }

    public function removePromotion(PromotionInterface $promotion): MembershipInterface
    {
        $promotion->setMembership(null);
        $this->promotions->removeElement($promotion);

        return $this;
    }

    public function setDurationOptions(?array $durationOptions): MembershipInterface
    {
        $this->durationOptions = $durationOptions;

        return $this;
    }

    public function getDurationOptions(): array
    {
        return $this->durationOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getMembershipCards(): Collection
    {
        return $this->membershipCards;
    }

    public function hasMembershipCard(MembershipCardInterface $membershipCard): bool
    {
        return (bool)$this->membershipCards->contains($membershipCard);
    }

    public function countMembershipCards(): int
    {
        return $this->membershipCards->count();
    }

    public function addMembershipCard(MembershipCardInterface $membershipCard): MembershipInterface
    {
        if (false === $this->hasMembershipCard($membershipCard)) {
            $membershipCard->setMembership($this);
            $this->membershipCards->add($membershipCard);
        }

        return $this;
    }

    public function removeMembershipCard(MembershipCardInterface $membershipCard): MembershipInterface
    {
        $membershipCard->setMembership(null);
        $this->membershipCards->removeElement($membershipCard);

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    /**
     * @param Club $club
     *
     * @return ClubAwareInterface|MembershipInterface
     */
    public function setClub(?Club $club): ClubAwareInterface
    {
        $this->club = $club;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsGitSync(): bool
    {
        return $this->isGitSync;
    }

    public function setIsGitSync(bool $isGitSync): self
    {
        $this->isGitSync = $isGitSync;

        return $this;
    }

    public function getIsHidden(): bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function getPlayRightOnly(): bool
    {
        return $this->playRightOnly;
    }

    public function setPlayRightOnly(bool $playRightOnly): self
    {
        $this->playRightOnly = $playRightOnly;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public static function getDurationTypes(): array
    {
        return [
            self::DURATION_12_MONTH,
            self::DURATION_ANNUAL,
        ];
    }

    /** @return Collection|MembershipFee[] */
    public function getFees(): Collection
    {
        return $this->fees;
    }

    public function addFee(FeeInterface $fee): SubjectFeeInterface
    {
        if (false === $this->hasFee($fee)) {
            $fee->setMembership($this);
            $this->fees->add($fee);
        }

        return $this;
    }

    public function removeFee(FeeInterface $fee): SubjectFeeInterface
    {
        $fee->setMembership(null);
        $this->fees->removeElement($fee);

        return $this;
    }

    public function hasFee(FeeInterface $fee): bool
    {
        return (bool)$this->fees->contains($fee);
    }

    /**
     * @deprecated
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @deprecated
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getMembershipCourses(): Collection
    {
        return $this->membershipCourses;
    }

    /**
     * @deprecated
     */
    public function addMembershipGolfCourse(MembershipCourse $membershipGolfCourse): self
    {
        if (false === $this->getMembershipCourses()->contains($membershipGolfCourse)) {
            $this->getMembershipCourses()->add($membershipGolfCourse);
            $membershipGolfCourse->setMembership($this);
        }

        return $this;
    }

    /**
     * @deprecated
     */
    public function getOwnerDiscount(): int
    {
        return $this->ownerDiscount ?? TeeTimeBooking::NON_MEMBER_DISCOUNT;
    }

    /**
     * @deprecated
     */
    public function setOwnerDiscount(?int $ownerDiscount): self
    {
        $this->ownerDiscount = $ownerDiscount;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getFriendDiscount(): int
    {
        return $this->friendDiscount ?? TeeTimeBooking::NON_MEMBER_DISCOUNT;
    }

    /**
     * @deprecated
     */
    public function setFriendDiscount(?int $friendDiscount): self
    {
        $this->friendDiscount = $friendDiscount;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getBookingParticipants(): Collection
    {
        return $this->bookingParticipants;
    }

    /**
     * @deprecated
     */
    public function addBookingParticipant(TeeTimeBookingParticipant $participant): self
    {
        if (false === $this->getBookingParticipants()->contains($participant)) {
            $this->getBookingParticipants()->add($participant);
            $participant->setOwnerMembership($this);
        }

        return $this;
    }

    /**
     * @deprecated
     */
    public function getMembershipCourseGroups(): Collection
    {
        return $this->membershipCourseGroups;
    }

    /**
     * @deprecated
     */
    public function addMembershipCourseGroups(MembershipCourseGroup $membershipCourseGroup): self
    {
        if (false === $this->getMembershipCourseGroups()->contains($membershipCourseGroup)) {
            $this->getMembershipCourseGroups()->add($membershipCourseGroup);
            $membershipCourseGroup->setMembership($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int|null $version
     */
    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }
}
