<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Domain\Promotion\Checker\Rule\RuleCheckerInterface;

class IsValidRuleInputValidator extends AbstractConfigurableElementValidator
{
    /** @var iterable|RuleCheckerInterface[] $iterable */
    protected $ruleCheckers;

    public function __construct(iterable $ruleCheckers)
    {
        $this->ruleCheckers = $ruleCheckers;
    }

    /**
     * @return iterable|RuleCheckerInterface[]
     */
    protected function getIterable(): iterable
    {
        return $this->ruleCheckers;
    }
}
