<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Rule;

use App\Application\Query\TeeTimeBooking\LimitQuery\LimitQuery;
use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;
use App\Domain\Promotion\Component\PromotionInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use InvalidArgumentException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Webmozart\Assert\Assert;

final class NumberOfSimultaneousBookingsRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'number_of_simultaneous_bookings_checker';

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

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
        $promotion = $rule->getPromotion();
        $limit = $this->getBookingsLimit($subject, $promotion);

        if ($configuration['number_of_simultaneous_bookings'] > $limit) {
            return true;
        }

        return false;
    }

    public function validateConfiguration(array $configuration): void
    {
        try {
            Assert::isArray($configuration);
            Assert::keyExists($configuration, 'number_of_simultaneous_bookings');
            Assert::integer($configuration['number_of_simultaneous_bookings']);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidConfigurablePromotionException();
        }
    }

    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionInterface $promotion
     *
     * @return int
     */
    private function getBookingsLimit(PromotionSubjectInterface $subject, PromotionInterface $promotion): int
    {
        $envelope = $this->messageBus->dispatch(new LimitQuery($subject, $promotion));

        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
