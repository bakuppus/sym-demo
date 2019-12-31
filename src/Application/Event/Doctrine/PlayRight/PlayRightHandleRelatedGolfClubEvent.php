<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\PlayRight;

use App\Domain\Player\PlayRight;

class PlayRightHandleRelatedGolfClubEvent
{
    /** @var PlayRight */
    protected $playRight;

    public function __construct(PlayRight $playRight)
    {
        $this->playRight = $playRight;
    }

    public function getPlayRight(): ?PlayRight
    {
        return $this->playRight;
    }
}
