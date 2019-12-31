<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\ExpiresAt;

use App\Application\Assigner\MembershipCard\ExpiresAt\Strategy\MembershipCardDurationTypeContext;
use App\Domain\Promotion\Core\MembershipCardInterface;
use DateTimeInterface;

final class MembershipCardExpiresAtAssigner implements MembershipCardExpiresAtAssignerInterface
{
    public function assignExpiresAtIfNotSet(MembershipCardInterface $membershipCard): void
    {
        if (null === $membershipCard->getExpiresAt()) {
            $this->assignExpiresAt($membershipCard);
        }
    }

    public function assignExpiresAt(MembershipCardInterface $membershipCard): void
    {
        $expiresAt = $this->resolveExpiresAt($membershipCard);

        $membershipCard->setExpiresAt($expiresAt);
    }

    private function resolveExpiresAt(MembershipCardInterface $membershipCard): ?DateTimeInterface
    {
        if (null === $membershipCard->getStartsAt() || null === $membershipCard->getDurationType()) {
            return null;
        }

        $context = new MembershipCardDurationTypeContext($membershipCard->getDurationType(), $membershipCard);

        return $context->execute();
    }
}
