<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\StartsAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use DateTimeInterface;

interface DurationTypeStrategyInterface
{
    public function validate(string $durationType): bool;

    public function execute(MembershipCardInterface $membershipCard): ?DateTimeInterface;
}
