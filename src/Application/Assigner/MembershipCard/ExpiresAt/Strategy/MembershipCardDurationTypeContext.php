<?php

declare(strict_types=1);

namespace App\Application\Assigner\MembershipCard\ExpiresAt\Strategy;

use App\Domain\Promotion\Core\MembershipCardInterface;
use DateTimeInterface;

class MembershipCardDurationTypeContext
{
    /** @var array|DurationTypeStrategyInterface[] */
    private $strategyClasses = [
        AnnualDurationTypeStrategy::class,
        TwelveMonthDurationTypeStrategy::class,
    ];

    /** @var DurationTypeStrategyInterface */
    private $strategy;

    /** @var MembershipCardInterface */
    private $membershipCard;

    public function __construct(string $durationType, MembershipCardInterface $membershipCard)
    {
        $this->membershipCard = $membershipCard;

        foreach ($this->strategyClasses as $strategy) {
            $this->strategy = new $strategy();
            if (false === $this->strategy->validate($durationType)) {
                $this->strategy = null;
                continue;
            }
            break;
        }
    }

    public function execute(): ?DateTimeInterface
    {
        if (null === $this->strategy) {
            return null;
        }

        return $this->strategy->execute($this->membershipCard);
    }
}
