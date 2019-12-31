<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Application\Command\Promotion\Crm\CancelMembershipCard\CancelMembershipCardCommand;
use App\Application\Command\Promotion\Crm\MarkAsPaidMembershipCard\MarkAsPaidMembershipCardCommand;
use App\Domain\Club\Club;
use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Order\Component\OrderInterface as BaseOrderInterface;
use App\Domain\Order\Core\OrderInterface;
use App\Domain\Player\Component\PlayerAwareInterface;
use App\Domain\Player\Player;
use App\Domain\Promotion\Component\MembershipCardInterface as BaseMembershipCardInterface;
use App\Domain\Promotion\Component\MembershipInterface as BaseMembershipInterface;
use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\Core\MembershipInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\MatchFilter;
use Cake\Chronos\Chronos;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="player_memberships", indexes={@ORM\Index(
 *     name="IDX_active_partner_membership",
 *     columns={"player_id", "golf_club_id", "duration_type", "is_active", "deleted_at"}
 * )})
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={
 *                      "Default", "get_fee", "list_memberships", "get_membership", "get_membership_card", "get_club"
 *                  }
 *              }
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"={"path"="/crm/membership-cards/{id}"},
 *          "mark_as_paid"={
 *              "method"="PUT",
 *              "path"="/crm/membership-cards/{id}/mark-as-paid",
 *              "input"=MarkAsPaidMembershipCardCommand::class,
 *              "denormalization_context"={"groups"={"Default", "mark_as_paid_membership_card"}},
 *              "normalization_context"={"groups"={"Default", "mark_as_paid_membership_card"}},
 *              "swagger_context"={
 *                  "summary"="Mark membership card as paid"
 *              },
 *              "validation_groups"={"Default", "mark_as_paid_membership_card"}
 *          },
 *          "cancel"={
 *              "method"="PUT",
 *              "path"="/crm/membership-cards/{id}/cancel",
 *              "input"=CancelMembershipCardCommand::class,
 *              "denormalization_context"={"groups"={"Default", "cancel_membership_card"}},
 *              "normalization_context"={"groups"={"Default", "cancel_membership_card"}},
 *              "swagger_context"={
 *                  "summary"="Cancel membership card"
 *              },
 *              "validation_groups"={"Default", "cancel_membership_card"}
 *          },
 *      }
 * )
 * @ApiFilter(MatchFilter::class, properties={"club.id", "player.id", "state"})
 * @ORM\HasLifecycleCallbacks
 */
class MembershipCard implements MembershipCardInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /** @deprecated */
    public const TYPE_PERSONAL = 'personal';
    /** @deprecated */
    public const TYPE_GROUP = 'group';
    /** @deprecated */
    public const TYPE_PARTNER = 'partner';

    /** Workflow */
    public const WORKFLOW_NAME = 'membership_card';

    /** Workflow places */
    public const STATE_INIT = 'init';
    public const STATE_NEW = 'new';
    public const STATE_PAID = 'paid';
    public const STATE_CANCELED = 'canceled';
    public const STATE_DELETED = 'deleted';

    public const STATE_LIST = [
        self::STATE_INIT,
        self::STATE_NEW,
        self::STATE_PAID,
        self::STATE_CANCELED,
        self::STATE_DELETED,
    ];

    /** Workflow transitions */
    public const TRANSITION_CREATE = 'create';
    public const TRANSITION_PAY = 'pay';
    public const TRANSITION_CANCEL = 'cancel';
    public const TRANSITION_REMOVE = 'remove';

    /** Status workflow */
    public const STATUS_WORKFLOW_NAME = 'membership_card_status';

    /** Status workflow places */
    public const STATUS_INIT = 'init';
    public const STATUS_FUTURE = 'future';
    public const STATUS_UPCOMING = 'upcoming';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_OLD = 'old';

    public const STATUS_LIST = [
        self::STATUS_INIT,
        self::STATUS_FUTURE,
        self::STATUS_UPCOMING,
        self::STATUS_ACTIVE,
        self::STATUS_OLD,
    ];

    /** Priority of statuses to show in player grid */
    public const STATUS_PRIORITY_SHOW = [
        self::STATUS_ACTIVE,
        self::STATUS_UPCOMING,
        self::STATUS_FUTURE,
        self::STATUS_INIT,
    ];

    /** Status workflow transitions */
    public const STATUS_TRANSITION_TO_FUTURE = 'to_future';
    public const STATUS_TRANSITION_TO_UPCOMING = 'to_upcoming';
    public const STATUS_TRANSITION_TO_ACTIVE = 'to_active';
    public const STATUS_TRANSITION_TO_OLD = 'to_old';

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="active_to", type="datetime", nullable=true)
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card",
     *     "cancel_membership_card"
     * })
     */
    private $expiresAt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="inscription_date", type="datetime", nullable=true)
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card",
     *     "cancel_membership_card"
     * })
     */
    private $startsAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card",
     *     "cancel_membership_card"
     * })
     */
    private $durationType;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="membershipCards", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"get_membership_card", "add_card_to_membership", "get_order_membership"})
     */
    private $player;

    /**
     * @var Membership
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="membershipCards", cascade={"persist"})
     * @Groups({"get_membership_card", "add_card_to_membership", "player_list", "get_order_membership"})
     */
    private $membership;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="membershipCards", cascade={"persist"})
     * @ORM\JoinColumn(name="golf_club_id")
     * @Groups({"get_membership_card", "add_card_to_membership", "get_order_membership"})
     */
    private $club;

    /**
     * @deprecated
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     *
     * @Groups({"player_list"})
     */
    private $isActive = false;

    /**
     * @deprecated
     *
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $type;

    /**
     * @deprecated
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $isSweetspot = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "player_list",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card"
     * })
     */
    private $state = self::STATE_INIT;

    /**
     * @var string
     *
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card",
     *     "player_list"
     * })
     */
    private $status = self::STATUS_INIT;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     * @Groups({
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card"
     * })
     */
    private $isManuallyPaid = false;

    /**
     * @var OrderInterface
     *
     * @Groups({"get_membership_card", "add_card_to_membership"})
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Order\OrderMembership", orphanRemoval=true)
     */
    private $order;

    /**
     * @var bool
     *
     * @Groups({
     *     "player_list",
     *     "get_membership_card",
     *     "add_card_to_membership",
     *     "get_order_membership",
     *     "mark_as_paid_membership_card"
     * })
     */
    private $isPaid;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Groups({"get_membership_card", "add_card_to_membership"})
     */
    private $calendarYear;

    /**
     * @deprecated
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @return PlayerAwareInterface|MembershipInterface
     */
    public function setPlayer(?Player $player): PlayerAwareInterface
    {
        $this->player = $player;

        return $this;
    }

    public function getMembership(): ?BaseMembershipInterface
    {
        return $this->membership;
    }

    public function setMembership(?BaseMembershipInterface $membership): BaseMembershipCardInterface
    {
        $this->membership = $membership;

        return $this;
    }

    public function setStartsAt(?DateTimeInterface $startsAt): BaseMembershipCardInterface
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setExpiresAt(?DateTimeInterface $expiresAt): BaseMembershipCardInterface
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isActive(): bool
    {
        $now = new DateTime();

        return $now > $this->getStartsAt() && $now < $this->getExpiresAt() && self::STATE_PAID === $this->state;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getDurationType(): ?string
    {
        return $this->durationType;
    }

    public function setDurationType(?string $durationType): BaseMembershipCardInterface
    {
        $this->durationType = $durationType;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getWorkflow(): string
    {
        return self::WORKFLOW_NAME;
    }

    public function removeTransitionName(): string
    {
        return self::TRANSITION_REMOVE;
    }

    public function getStatus(): string
    {
        $now = Chronos::now();
        if ($now > $this->getStartsAt() && $now < $this->getExpiresAt() && self::STATE_PAID === $this->state) {
            return self::STATUS_ACTIVE;
        }

        if ($now < $this->getStartsAt() && self::STATE_PAID === $this->state) {
            return self::STATUS_UPCOMING;
        }

        if (
            ($now > $this->getExpiresAt() && self::STATE_PAID === $this->state)
            || self::STATE_CANCELED === $this->state || self::STATE_DELETED === $this->state
        ) {
            return self::STATUS_OLD;
        }

        if (
            $this->durationType === Membership::DURATION_ANNUAL
            && $now < $this->expiresAt && $this->state === self::STATE_NEW
            || $this->durationType === Membership::DURATION_12_MONTH
            && null === $this->expiresAt && $this->state === self::STATE_NEW
        ) {
            return self::STATUS_FUTURE;
        }

        return self::STATUS_INIT;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isManuallyPaid(): bool
    {
        return $this->isManuallyPaid;
    }

    public function getCalendarYear(): ?DateTime
    {
        return $this->calendarYear;
    }

    public function setCalendarYear(?DateTime $calendarYear): self
    {
        $this->calendarYear = $calendarYear;

        return $this;
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    public function setCalendarYearFromString(?string $calendarYear): self
    {
        if (null !== $calendarYear) {
            $this->setCalendarYear(new DateTime($calendarYear));
        }

        return $this;
    }

    public function setIsManuallyPaid(bool $isManuallyPaid): self
    {
        $this->isManuallyPaid = $isManuallyPaid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): ?BaseOrderInterface
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(?BaseOrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getOldIsActive(): bool
    {
        return $this->isActive;
    }

    public function getIsPaid(): bool
    {
        return self::STATE_PAID === $this->getState();
    }

    public function getStateForOld(): string
    {
        if (true === $this->getOldIsActive() && null === $this->getDeletedAt()) {
            return MembershipCard::STATE_PAID;
        }

        if (false === $this->getOldIsActive() && null === $this->getDeletedAt()) {
            return MembershipCard::STATE_NEW;
        }

        if (true === $this->getOldIsActive() && null !== $this->getDeletedAt()) {
            return MembershipCard::STATE_CANCELED;
        }

        if (false === $this->getOldIsActive() && null !== $this->getDeletedAt()) {
            return MembershipCard::STATE_DELETED;
        }

        return MembershipCard::STATE_DELETED;
    }

    /**
     * Returns deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function isSweetspot(): bool
    {
        return $this->isSweetspot;
    }

    public function setIsSweetspot(bool $isSweetspot): self
    {
         $this->isSweetspot = $isSweetspot;

         return $this;
    }
}
