<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\StartsAt;

use App\Application\Assigner\MembershipCard\StartsAt\Strategy\MembershipCardDurationTypeContext;
use App\Domain\Promotion\Core\MembershipCardInterface;
use DateTimeInterface;

final class MembershipCardStartsAtAssigner implements MembershipCardStartsAtAssignerInterface
{
    public function assignStartsAtIfNotSet(MembershipCardInterface $membershipCard): void
    {
        if (null === $membershipCard->getStartsAt() && null !== $membershipCard->getDurationType()) {
            $this->assignStartsAt($membershipCard);
        }
    }

    public function assignStartsAt(MembershipCardInterface $membershipCard): void
    {
        $startsAt = $this->resolveStartsAt($membershipCard);

        $membershipCard->setStartsAt($startsAt);
    }

    private function resolveStartsAt(MembershipCardInterface $membershipCard): ?DateTimeInterface
    {
        $context = new MembershipCardDurationTypeContext($membershipCard->getDurationType(), $membershipCard);

        return $context->execute();
    }
}
