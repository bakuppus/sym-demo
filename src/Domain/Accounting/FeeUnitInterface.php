<?php

declare(strict_types=1);

namespace App\Domain\Accounting;

interface FeeUnitInterface
{
    public function getName(): ?string;

    public function setName(?string $name): FeeUnitInterface;
}
