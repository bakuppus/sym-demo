<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Applicator;

use App\Domain\Promotion\Action\PromotionActionCommandInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Exception\InvalidCommandActionException;

final class PromotionApplicator implements PromotionApplicatorInterface
{
    /** @var PromotionActionCommandInterface[]|iterable */
    private $actionCommands;

    public function __construct(iterable $actionCommands)
    {
        $this->actionCommands = $actionCommands;
    }

    public function apply(PromotionSubjectInterface $subject, PromotionInterface $promotion): void
    {
        $applyPromotion = false;
        foreach ($promotion->getActions() as $action) {
            $result = $this
                ->getActionCommandByType($action->getType())
                ->execute($subject, $action->getConfiguration(), $promotion);

            $applyPromotion = $applyPromotion || $result;
        }

        if (true === $applyPromotion) {
            $subject->addPromotion($promotion);
        }
    }

    public function revert(PromotionSubjectInterface $subject, PromotionInterface $promotion): void
    {
        foreach ($promotion->getActions() as $action) {
            $this->getActionCommandByType($action->getType())
                ->revert($subject, $action->getConfiguration(), $promotion);
        }

        $subject->removePromotion($promotion);
    }

    private function getActionCommandByType(string $type): PromotionActionCommandInterface
    {
        foreach ($this->actionCommands as $actionCommand) {
            if ($type === $actionCommand->getType()) {
                return $actionCommand;
            }
        }

        throw new InvalidCommandActionException();
    }
}