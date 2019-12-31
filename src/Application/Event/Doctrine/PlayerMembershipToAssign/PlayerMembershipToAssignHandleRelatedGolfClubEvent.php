<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\PlayerMembershipToAssign;

use App\Domain\Membership\PlayerMembershipToAssign;

class PlayerMembershipToAssignHandleRelatedGolfClubEvent
{
    /** @var PlayerMembershipToAssign */
    protected $playerMembershipToAssign;

    public function __construct(PlayerMembershipToAssign $playerMembershipToAssign)
    {
        $this->playerMembershipToAssign = $playerMembershipToAssign;
    }

    public function getPlayerMembershipToAssign(): ?PlayerMembershipToAssign
    {
        return $this->playerMembershipToAssign;
    }
}
