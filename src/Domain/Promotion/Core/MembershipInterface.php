<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Core;

use App\Domain\Accounting\SubjectFeeInterface;
use App\Domain\Club\Component\ClubAwareInterface;
use Doctrine\Common\Collections\Collection;
use App\Domain\Promotion\Component\MembershipInterface as BaseMembershipInterface;

interface MembershipInterface extends BaseMembershipInterface, ClubAwareInterface, SubjectFeeInterface
{
    /**
     * @return Collection|PromotionInterface[]
     */
    public function getPromotions(): Collection;

    public function hasPromotions(): bool;

    public function hasPromotion(PromotionInterface $promotion): bool;

    public function addPromotion(PromotionInterface $promotion): MembershipInterface;

    public function removePromotion(PromotionInterface $promotion): MembershipInterface;

    /**
     * @return Collection|MembershipCardInterface[]
     */
    public function getMembershipCards(): Collection;

    public function hasMembershipCard(MembershipCardInterface $membershipCard): bool;

    public function countMembershipCards(): int;

    public function addMembershipCard(MembershipCardInterface $membershipCard): MembershipInterface;

    public function removeMembershipCard(MembershipCardInterface $membershipCard): MembershipInterface;
}