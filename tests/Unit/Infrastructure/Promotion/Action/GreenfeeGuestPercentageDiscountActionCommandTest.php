<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Action;

use App\Infrastructure\Promotion\Action\GreenfeeGuestPercentageDiscountActionCommand;
use PHPUnit\Framework\TestCase;

class GreenfeeGuestPercentageDiscountActionCommandTest extends TestCase
{
    public function testType()
    {
        $actionCommand = new GreenfeeGuestPercentageDiscountActionCommand();
        $this->assertEquals('greenfee_guest_percentage_discount', $actionCommand->getType());
    }
}