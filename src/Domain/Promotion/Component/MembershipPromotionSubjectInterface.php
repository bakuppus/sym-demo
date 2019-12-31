<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface MembershipPromotionSubjectInterface extends PromotionSubjectInterface
{
    public function getMembership(): ?MembershipInterface;
}