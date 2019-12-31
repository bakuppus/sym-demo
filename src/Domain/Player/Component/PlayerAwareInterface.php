<?php

declare(strict_types=1);

namespace App\Domain\Player\Component;

use App\Domain\Player\Player;

interface PlayerAwareInterface
{
    public function getPlayer(): ?Player;

    public function setPlayer(?Player $player): self;
}
