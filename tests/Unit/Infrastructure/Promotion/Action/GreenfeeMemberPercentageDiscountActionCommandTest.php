<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Action;

use App\Infrastructure\Promotion\Action\GreenfeeMemberPercentageDiscountActionCommand;
use PHPUnit\Framework\TestCase;

class GreenfeeMemberPercentageDiscountActionCommandTest extends TestCase
{
    public function testType()
    {
        $actionCommand = new GreenfeeMemberPercentageDiscountActionCommand();
        $this->assertEquals('greenfee_member_percentage_discount', $actionCommand->getType());
    }
}