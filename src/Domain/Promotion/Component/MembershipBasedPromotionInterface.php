<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface MembershipBasedPromotionInterface
{
    public function isMembershipBased(): bool;

    public function getMembership(): ?MembershipInterface;

    public function setMembership(?MembershipInterface $membership): self;
}