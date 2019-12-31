<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker\Eligibility;

use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;

final class PromotionRulesEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /** @var iterable|RuleCheckerInterface[] */
    private $ruleCheckers;

    public function __construct(iterable $ruleCheckers)
    {
        $this->ruleCheckers = $ruleCheckers;
    }

    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if (false === $promotion->hasRules()) {
            return true;
        }

        foreach ($promotion->getRules() as $rule) {
            if (false === $this->isEligibleToRule($promotionSubject, $rule)) {
                return false;
            }
        }

        return true;
    }

    private function isEligibleToRule(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool
    {
        foreach ($this->ruleCheckers as $checker) {
            if ($rule->getType() === $checker->getType()) {
                return $checker->isEligible($subject, $rule);
            }
        }

        return false;
    }
}