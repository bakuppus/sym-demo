<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use DateTimeInterface;

interface MembershipCardInterface
{
    public function getMembership(): ?MembershipInterface;

    public function setMembership(?MembershipInterface $membership): self;

    public function getStartsAt(): ?DateTimeInterface;

    public function setStartsAt(?DateTimeInterface $startsAt): self;

    public function getExpiresAt(): ?DateTimeInterface;

    public function setExpiresAt(?DateTimeInterface $endsAt): self;

    public function isActive(): bool;

    public function getDurationType(): ?string;

    public function setDurationType(?string $durationType): self;
}
