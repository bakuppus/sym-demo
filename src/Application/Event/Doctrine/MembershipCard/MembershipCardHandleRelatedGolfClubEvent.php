<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\MembershipCard;

use App\Domain\Promotion\MembershipCard;

class MembershipCardHandleRelatedGolfClubEvent
{
    /** @var MembershipCard */
    protected $membershipCard;

    public function __construct(MembershipCard $membershipCard)
    {
        $this->membershipCard = $membershipCard;
    }

    public function getMembershipCard(): ?MembershipCard
    {
        return $this->membershipCard;
    }
}
