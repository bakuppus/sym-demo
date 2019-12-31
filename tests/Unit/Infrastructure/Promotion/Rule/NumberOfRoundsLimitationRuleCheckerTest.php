<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Rule;

use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Infrastructure\Promotion\Rule\NumberOfRoundsLimitationRuleChecker;
use PHPUnit\Framework\TestCase;

final class NumberOfRoundsLimitationRuleCheckerTest extends TestCase
{
    public function testType()
    {
        $ruleChecker = new NumberOfRoundsLimitationRuleChecker();
        $this->assertEquals('number_of_rounds_limitation_checker', $ruleChecker->getType());
    }

    public function testValidateConfiguration()
    {
        $configuration = ['limitation_value' => 10];

        $ruleChecker = new NumberOfRoundsLimitationRuleChecker();
        $ruleChecker->validateConfiguration($configuration);

        $this->assertTrue(true);
    }

    public function testValidateWrongConfiguration()
    {
        $this->expectException(InvalidConfigurablePromotionException::class);

        $configuration = ['wrong_config' => 10];

        $ruleChecker = new NumberOfRoundsLimitationRuleChecker();
        $ruleChecker->validateConfiguration($configuration);
    }
}
