<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Component;

interface MembershipInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function setTotal(int $price): self;

    public function getTotal(): int;

    public function setDurationOptions(?array $durationOptions): \App\Domain\Promotion\Core\MembershipInterface;

    public function getDurationOptions(): ?array;
}
