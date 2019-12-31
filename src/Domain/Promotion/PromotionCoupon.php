<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use App\Domain\Promotion\Component\PromotionCouponInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PromotionCoupon implements PromotionCouponInterface
{
    use AutoTrait;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $usageLimit;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $used = 0;

    /**
     * @var PromotionInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Promotion", inversedBy="coupons")
     */
    private $promotion;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiresAt;

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(?int $usageLimit): PromotionCouponInterface
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    public function setUsed(int $used): PromotionCouponInterface
    {
        $this->used = $used;

        return $this;
    }

    public function incrementUsed(): PromotionCouponInterface
    {
        ++$this->used;

        return $this;
    }

    public function decrementUsed(): PromotionCouponInterface
    {
        --$this->used;

        return $this;
    }

    /**
     * @return BasePromotionInterface|Promotion|null
     */
    public function getPromotion(): ?BasePromotionInterface
    {
        return $this->promotion;
    }

    public function setPromotion(?BasePromotionInterface $promotion): PromotionCouponInterface
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeInterface $expiresAt): PromotionCouponInterface
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isValid(): bool
    {
        if (null !== $this->usageLimit && $this->used >= $this->usageLimit) {
            return false;
        }

        if (null !== $this->expiresAt && $this->expiresAt < new DateTime()) {
            return false;
        }

        return true;
    }
}