<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Rule;

use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Domain\Promotion\Exception\InvalidPromotionSubjectException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class IncludedCoursesRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'included_courses_checker';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param PromotionSubjectInterface|TeeTimeBookingParticipant $subject
     * @param PromotionRuleInterface $rule
     *
     * @return bool
     */
    public function isEligible(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool
    {
        $configuration = $rule->getConfiguration();

        try {
            $this->isSubjectValid($subject);
            $this->validateConfiguration($configuration);
        } catch (InvalidConfigurablePromotionException | InvalidPromotionSubjectException $exception) {
            return false;
        }

        $courseId = $subject->getBooking()->getCourse()->getId() ?? null;

        if (true === in_array($courseId, $configuration)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $configuration): void
    {
        try {
            Assert::isArray($configuration);
            Assert::allInteger($configuration);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidConfigurablePromotionException();
        }
    }

    protected function isSubjectValid(PromotionSubjectInterface $subject): void
    {
        try {
            Assert::isInstanceOf($subject, TeeTimeBookingParticipant::class);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidPromotionSubjectException();
        }
    }
}