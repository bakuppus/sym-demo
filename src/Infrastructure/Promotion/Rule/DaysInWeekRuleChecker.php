<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Rule;

use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use DateTime;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use Exception;

final class DaysInWeekRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'days_in_week_checker';

    private const DAY_NAMES = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionRuleInterface $rule
     *
     * @return bool
     * @throws Exception
     */
    public function isEligible(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool
    {
        $configuration = $rule->getConfiguration();
        try {
            $this->validateConfiguration($configuration);
        } catch (InvalidConfigurablePromotionException $exception) {
            return false;
        }

        $today = new DateTime();
        $dayName = $today->format('l');

        return in_array($dayName, $configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $configuration): void
    {
        try {
            Assert::isArray($configuration);
            Assert::allString($configuration);
            Assert::uniqueValues($configuration);

            foreach ($configuration as $item) {
                Assert::oneOf($item, self::DAY_NAMES);
            }
        } catch (InvalidArgumentException $exception) {
            throw new InvalidConfigurablePromotionException();
        }
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public static function getDayNames(): array
    {
        return self::DAY_NAMES;
    }
}
