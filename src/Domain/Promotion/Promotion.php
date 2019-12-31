<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Club\Club;
use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Promotion\Component\PromotionActionInterface;
use App\Domain\Promotion\Component\PromotionCouponInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\CouponBasedPromotionInterface;
use App\Domain\Promotion\Component\PromotionRuleAwareInterface;
use App\Domain\Promotion\Component\PromotionActionAwareInterface;
use App\Domain\Promotion\Component\MembershipBasedPromotionInterface;
use App\Domain\Promotion\Core\MembershipInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Domain\Promotion\Component\MembershipInterface as BaseMembershipInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Application\Command\Promotion\Crm\AddNewAction\AddNewActionCommand;
use App\Application\Command\Promotion\Crm\AddNewRule\AddNewRuleCommand;
use App\Application\Command\Promotion\Crm\UpdatePromotion\UpdatePromotionCommand;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={},
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"Default", "add_promotion_to_membership"}},
 *              "denormalization_context"={"groups"={"Default", "add_promotion_to_membership"}},
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/crm/promotions/{id}",
 *              "input"=UpdatePromotionCommand::class,
 *              "normalization_context"={"groups"={"Default", "add_promotion_to_membership"}},
 *              "denormalization_context"={"groups"={"Default", "edit_promotion"}},
 *              "swagger_context"={
 *                  "summary"="Update rule set"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "add_new_rule"={
 *              "method"="PUT",
 *              "path"="/crm/promotions/{id}/rule/new",
 *              "input"=AddNewRuleCommand::class,
 *              "normalization_context"={"groups"={"Default", "add_new_rule"}},
 *              "denormalization_context"={"groups"={"Default", "add_new_rule"}},
 *              "swagger_context"={
 *                  "summary"="Add new rule"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "add_new_action"={
 *              "method"="PUT",
 *              "path"="/crm/promotions/{id}/action/new",
 *              "input"=AddNewActionCommand::class,
 *              "normalization_context"={"groups"={"Default", "add_new_action"}},
 *              "denormalization_context"={"groups"={"Default", "add_new_action"}},
 *              "swagger_context"={
 *                  "summary"="Add new action"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "delete"={"path"="/crm/promotions/{id}"}
 *     }
 * )
 */
class Promotion implements PromotionInterface
{
    use AutoTrait;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"get_membership", "add_promotion_to_membership"})
     * @Gedmo\Slug(fields={"name"}, unique=true, updatable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"get_membership", "add_promotion_to_membership"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * When exclusive, promotion with top priority will be applied
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\SortablePosition
     *
     * @Groups({"get_membership", "add_promotion_to_membership"})
     */
    private $priority;

    /**
     * Cannot be applied together with other promotions
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $exclusive = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $usageLimit;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $used = 0;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startsAt;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endsAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $couponBased = false;

    /**
     * @var MembershipInterface|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="promotions")
     * @Gedmo\SortableGroup
     * @Assert\Valid(groups={"edit_membership"})
     */
    private $membership;

    /**
     * @var Club|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club")
     */
    private $club;

    /**
     * @var Collection|PromotionCouponInterface[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\PromotionCoupon", mappedBy="promotion")
     */
    private $coupons;

    /**
     * @var Collection|PromotionRuleInterface[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\PromotionRule", mappedBy="promotion", cascade={"remove"})
     *
     * @Groups({"get_membership"})
     */
    private $rules;

    /**
     * @var Collection|PromotionActionInterface[]
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Promotion\PromotionAction", mappedBy="promotion", cascade={"remove"})
     *
     * @Groups({"get_membership"})
     */
    private $actions;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->coupons = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     *
     * @return PromotionInterface|PromotionInterface
     */
    public function setCode(?string $code): PromotionInterface
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setName(?string $name): BasePromotionInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setDescription(?string $description): BasePromotionInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setPriority(?int $priority): BasePromotionInterface
    {
        $this->priority = $priority ?? -1;

        return $this;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @param bool|null $exclusive
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setExclusive(?bool $exclusive): BasePromotionInterface
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    /**
     * @param int|null $usageLimit
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setUsageLimit(?int $usageLimit): BasePromotionInterface
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    /**
     * @param int|null $used
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setUsed(?int $used): BasePromotionInterface
    {
        $this->used = $used;

        return $this;
    }

    /**
     * @return BasePromotionInterface|PromotionInterface
     */
    public function incrementUsed(): BasePromotionInterface
    {
        ++$this->used;

        return $this;
    }

    /**
     * @return BasePromotionInterface|PromotionInterface
     */
    public function decrementUsed(): BasePromotionInterface
    {
        --$this->used;

        return $this;
    }

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @param DateTimeInterface|null $startsAt
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setStartsAt(?DateTimeInterface $startsAt): BasePromotionInterface
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?DateTimeInterface
    {
        return $this->endsAt;
    }

    /**
     * @param DateTimeInterface|null $endsAt
     *
     * @return BasePromotionInterface|PromotionInterface
     */
    public function setEndsAt(?DateTimeInterface $endsAt): BasePromotionInterface
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function isCouponBased(): bool
    {
        return $this->couponBased;
    }

    /**
     * @param bool|null $couponBased
     *
     * @return CouponBasedPromotionInterface|PromotionInterface
     */
    public function setCouponBased(?bool $couponBased): CouponBasedPromotionInterface
    {
        $this->couponBased = (bool)$couponBased;

        return $this;
    }

    /**
     * @return Collection|PromotionCouponInterface[]
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function hasCoupons(): bool
    {
        return false === $this->coupons->isEmpty();
    }

    public function hasCoupon(PromotionCouponInterface $coupon): bool
    {
        return $this->coupons->contains($coupon);
    }

    /**
     * @param PromotionCouponInterface $coupon
     *
     * @return CouponBasedPromotionInterface|PromotionInterface
     */
    public function addCoupon(PromotionCouponInterface $coupon): CouponBasedPromotionInterface
    {
        if (false === $this->hasCoupon($coupon)) {
            $coupon->setPromotion($this);
            $this->coupons->add($coupon);
        }

        return $this;
    }

    /**
     * @param PromotionCouponInterface $coupon
     *
     * @return CouponBasedPromotionInterface|PromotionInterface
     */
    public function removeCoupon(PromotionCouponInterface $coupon): CouponBasedPromotionInterface
    {
        $coupon->setPromotion(null);
        $this->coupons->removeElement($coupon);

        return $this;
    }

    /**
     * @return Collection|PromotionRuleInterface[]
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return false === $this->rules->isEmpty();
    }

    public function hasRule(PromotionRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    /**
     * @param PromotionRuleInterface $rule
     *
     * @return PromotionRuleAwareInterface|PromotionInterface
     */
    public function addRule(PromotionRuleInterface $rule): PromotionRuleAwareInterface
    {
        if (false === $this->hasRule($rule)) {
            $rule->setPromotion($this);
            $this->rules->add($rule);
        }

        return $this;
    }

    /**
     * @param PromotionRuleInterface $rule
     *
     * @return PromotionRuleAwareInterface|PromotionInterface
     */
    public function removeRule(PromotionRuleInterface $rule): PromotionRuleAwareInterface
    {
        $rule->setPromotion(null);
        $this->rules->removeElement($rule);

        return $this;
    }

    /**
     * @return Collection|PromotionActionInterface[]
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function hasActions(): bool
    {
        return false === $this->actions->isEmpty();
    }

    public function hasAction(PromotionActionInterface $action): bool
    {
        return $this->actions->contains($action);
    }

    /**
     * @param PromotionActionInterface $action
     *
     * @return PromotionActionAwareInterface|PromotionInterface
     */
    public function addAction(PromotionActionInterface $action): PromotionActionAwareInterface
    {
        if (false === $this->hasAction($action)) {
            $action->setPromotion($this);
            $this->actions->add($action);
        }

        return $this;
    }

    /**
     * @param PromotionActionInterface $action
     *
     * @return PromotionActionAwareInterface|PromotionInterface
     */
    public function removeAction(PromotionActionInterface $action): PromotionActionAwareInterface
    {
        $action->setPromotion(null);
        $this->actions->removeElement($action);

        return $this;
    }

    public function isMembershipBased(): bool
    {
        return (bool)$this->membership;
    }

    /**
     * @return BaseMembershipInterface|MembershipInterface|null
     */
    public function getMembership(): ?BaseMembershipInterface
    {
        return $this->membership;
    }

    /**
     * @param BaseMembershipInterface|null $membership
     *
     * @return MembershipBasedPromotionInterface|MembershipInterface
     */
    public function setMembership(?BaseMembershipInterface $membership): MembershipBasedPromotionInterface
    {
        $this->membership = $membership;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    /**
     * @param Club|null $club
     *
     * @return ClubAwareInterface|MembershipInterface
     */
    public function setClub(?Club $club): ClubAwareInterface
    {
        $this->club = $club;

        return $this;
    }
}