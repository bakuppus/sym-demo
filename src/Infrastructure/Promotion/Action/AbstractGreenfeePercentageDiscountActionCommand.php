<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Action;

use App\Domain\Booking\TeeTimeBookingParticipant;
use App\Domain\Promotion\Action\PromotionActionCommandInterface;
use App\Domain\Promotion\Component\MembershipPromotionSubjectInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

abstract class AbstractGreenfeePercentageDiscountActionCommand implements PromotionActionCommandInterface
{
    private const CONFIGURATION_KEY = 'percentage_coefficient';

    /**
     * //todo: implementation of applying discount is next features
     *
     * {@inheritdoc}
     *
     * @param TeeTimeBookingParticipant|PromotionSubjectInterface $subject
     */
    public function execute(
        PromotionSubjectInterface $subject,
        array $configuration,
        PromotionInterface $promotion
    ): bool {
        if (false === $this->isSubjectValid($subject, $promotion)) {
            return false;
        }

        try {
            $this->validateConfiguration($configuration);
        } catch (InvalidConfigurablePromotionException $exception) {
            return false;
        }

        $promotionAmount = $this->calculateAdjustmentAmount(
            $subject->getPromotionSubjectTotal(),
            $configuration[self::CONFIGURATION_KEY]
        );

        if (0 === $promotionAmount) {
            return false;
        }

        $subject->setPrice($subject->getPromotionSubjectTotal() + $promotionAmount);

        return true;
    }

    /**
     * //todo: implementation of reverting discount is next features
     *
     * {@inheritdoc}
     *
     * @param TeeTimeBookingParticipant|PromotionSubjectInterface $subject
     */
    public function revert(
        PromotionSubjectInterface $subject,
        array $configuration,
        PromotionInterface $promotion
    ): void {
        if (false === $this->isSubjectValid($subject, $promotion)) {
            return;
        }

        try {
            $this->validateConfiguration($configuration);
        } catch (InvalidConfigurablePromotionException $exception) {
            return;
        }

        $promotionAmount = $this->calculateAdjustmentAmount(
            $subject->getPromotionSubjectTotal(),
            $configuration[self::CONFIGURATION_KEY]
        );

        $subject->setPrice($subject->getPrice() - $promotionAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $configuration): void
    {
        try {
            Assert::keyExists($configuration, self::CONFIGURATION_KEY);
            Assert::greaterThan($configuration[self::CONFIGURATION_KEY], 0);
            Assert::lessThanEq($configuration[self::CONFIGURATION_KEY], 1);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidConfigurablePromotionException();
        }
    }

    abstract public function getType(): string;

    protected function calculateAdjustmentAmount(int $promotionSubjectTotal, float $percentage): int
    {
        return -1 * (int)round($promotionSubjectTotal * $percentage);
    }

    /**
     * @param PromotionSubjectInterface|MembershipPromotionSubjectInterface|TeeTimeBookingParticipant $subject
     * @param PromotionInterface $promotion
     *
     * @return bool
     */
    protected function isSubjectValid(PromotionSubjectInterface $subject, PromotionInterface $promotion): bool
    {
        Assert::isInstanceOf($subject, MembershipPromotionSubjectInterface::class);
        Assert::isInstanceOf($subject, TeeTimeBookingParticipant::class);

        return $subject->getMembership()->hasPromotion($promotion);
    }
}