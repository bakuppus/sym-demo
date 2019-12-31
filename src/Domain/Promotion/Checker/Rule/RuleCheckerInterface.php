<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Checker\Rule;

use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Component\ConfigurableCommandInterface;

interface RuleCheckerInterface extends ConfigurableCommandInterface
{
    public function isEligible(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool;
}
