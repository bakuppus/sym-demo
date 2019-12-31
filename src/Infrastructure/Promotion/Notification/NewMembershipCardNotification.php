<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Notification;

use App\Domain\Promotion\MembershipCard;

class NewMembershipCardNotification
{
    private $membershipCard;

    public function __construct(MembershipCard $membershipCard)
    {
        $this->membershipCard = $membershipCard;
    }

    public function getMembershipCard(): MembershipCard
    {
        return $this->membershipCard;
    }
}
