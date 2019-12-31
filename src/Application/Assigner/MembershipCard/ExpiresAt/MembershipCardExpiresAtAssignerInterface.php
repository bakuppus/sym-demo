<?php

namespace App\Application\Assigner\MembershipCard\ExpiresAt;

use App\Domain\Promotion\Core\MembershipCardInterface;

interface MembershipCardExpiresAtAssignerInterface
{
    public function assignExpiresAt(MembershipCardInterface $membershipCard): void;

    public function assignExpiresAtIfNotSet(MembershipCardInterface $membershipCard): void;
}
