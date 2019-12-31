<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Rule;

use App\Infrastructure\Promotion\Rule\IncludedCoursesRuleChecker;
use PHPUnit\Framework\TestCase;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;

class IncludedCoursesRuleCheckerTest extends TestCase
{
    public function testType()
    {
        $ruleChecker = new IncludedCoursesRuleChecker();
        $this->assertEquals('included_courses_checker', $ruleChecker->getType());
    }

    public function testValidateConfiguration()
    {
        $configuration = [12, 13, 22];

        $ruleChecker = new IncludedCoursesRuleChecker();
        $ruleChecker->validateConfiguration($configuration);

        $this->assertTrue(true);
    }

    public function testValidateWrongConfiguration()
    {
        $this->expectException(InvalidConfigurablePromotionException::class);

        $configuration = ['12', false, 22];

        $ruleChecker = new IncludedCoursesRuleChecker();
        $ruleChecker->validateConfiguration($configuration);
    }
}
