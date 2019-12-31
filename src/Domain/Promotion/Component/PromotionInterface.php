<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

use DateTimeInterface;

interface PromotionInterface
{
    public function getName(): ?string;

    public function setName(?string $name): self;

    public function getDescription(): ?string;

    public function setDescription(?string $description): self;

    public function getPriority(): ?int;

    public function setPriority(?int $priority): self;

    public function isExclusive(): bool;

    public function setExclusive(?bool $exclusive): self;

    public function getUsageLimit(): ?int;

    public function setUsageLimit(?int $usageLimit): self;

    public function getUsed(): int;

    public function setUsed(int $used): self;

    public function incrementUsed(): self;

    public function decrementUsed(): self;

    public function getStartsAt(): ?DateTimeInterface;

    public function setStartsAt(?DateTimeInterface $startsAt): self;

    public function getEndsAt(): ?DateTimeInterface;

    public function setEndsAt(?DateTimeInterface $endsAt): self;
}