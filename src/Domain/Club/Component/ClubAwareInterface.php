<?php

declare(strict_types=1);

namespace App\Domain\Club\Component;

use App\Domain\Club\Club;

interface ClubAwareInterface
{
    public function getClub(): ?Club;

    public function setClub(?Club $club): self;
}