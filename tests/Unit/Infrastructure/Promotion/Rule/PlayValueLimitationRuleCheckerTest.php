<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Rule;

use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Infrastructure\Promotion\Rule\PlayValueLimitationRuleChecker;
use PHPUnit\Framework\TestCase;

final class PlayValueLimitationRuleCheckerTest extends TestCase
{
    public function testType()
    {
        $ruleChecker = new PlayValueLimitationRuleChecker();
        $this->assertEquals('play_value_limitation_checker', $ruleChecker->getType());
    }

    public function testValidateConfiguration()
    {
        $configuration = ['limitation_value' => 10];

        $ruleChecker = new PlayValueLimitationRuleChecker();
        $ruleChecker->validateConfiguration($configuration);

        $this->assertTrue(true);
    }

    public function testValidateWrongConfiguration()
    {
        $this->expectException(InvalidConfigurablePromotionException::class);

        $configuration = ['wrong_config' => 10];

        $ruleChecker = new PlayValueLimitationRuleChecker();
        $ruleChecker->validateConfiguration($configuration);
    }
}