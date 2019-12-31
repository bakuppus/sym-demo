<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Action;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;

final class GreenfeeGuestPercentageDiscountActionCommand extends AbstractGreenfeePercentageDiscountActionCommand
{
    public const TYPE = 'greenfee_guest_percentage_discount';

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    protected function isSubjectValid(PromotionSubjectInterface $subject, PromotionInterface $promotion): bool
    {
        return true === parent::isSubjectValid($subject, $promotion) && false === $subject->isMember();
    }
}
