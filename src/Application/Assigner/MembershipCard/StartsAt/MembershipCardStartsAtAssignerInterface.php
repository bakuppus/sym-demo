<?php

namespace App\Application\Assigner\MembershipCard\StartsAt;

use App\Domain\Promotion\Core\MembershipCardInterface;

interface MembershipCardStartsAtAssignerInterface
{
    public function assignStartsAt(MembershipCardInterface $membershipCard): void;

    public function assignStartsAtIfNotSet(MembershipCardInterface $membershipCard): void;
}
