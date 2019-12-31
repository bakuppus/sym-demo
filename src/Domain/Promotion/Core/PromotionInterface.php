<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Core;

use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Promotion\Component\PromotionActionAwareInterface;
use App\Domain\Promotion\Component\CouponBasedPromotionInterface;
use App\Domain\Promotion\Component\MembershipBasedPromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Domain\Promotion\Component\PromotionRuleAwareInterface;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;

interface PromotionInterface extends BasePromotionInterface,
    PromotionRuleAwareInterface,
    PromotionActionAwareInterface,
    CouponBasedPromotionInterface,
    MembershipBasedPromotionInterface,
    ClubAwareInterface,
    DeleteCommandAwareInterface
{
}
