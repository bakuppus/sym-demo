<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Rule;

use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Domain\Promotion\MembershipCard;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class PlayValueLimitationRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'play_value_limitation_checker';

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionRuleInterface $rule
     *
     * @return bool
     */
    public function isEligible(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool
    {
        $configuration = $rule->getConfiguration();

        try {
            $this->validateConfiguration($configuration);
        } catch (InvalidArgumentException $exception) {
            return false;
        }

        // TODO: after auth implementation delete "return true" (40 line)
        // TODO: and implement getMembershipCard() method

        return true;

        $membershipCard = $this->getMembershipCard();

        if (true === ($membershipCard->getPlayValue() < $configuration['limitation_value'])) {
            return true;
        }

        return false;
    }

    /**
     * @return MembershipCard
     */
    protected function getMembershipCard(): MembershipCard
    {
        $memberShipCard = 'todo';

        return $memberShipCard;
    }

    public function validateConfiguration(array $configuration): void
    {
        try {
            Assert::isArray($configuration);
            Assert::keyExists($configuration, 'limitation_value');
            Assert::integer($configuration['limitation_value']);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidConfigurablePromotionException();
        }
    }
}
