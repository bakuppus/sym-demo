<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Action;

use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Infrastructure\Promotion\Action\AbstractGreenfeePercentageDiscountActionCommand;
use PHPUnit\Framework\TestCase;

final class AbstractGreenfeePercentageDiscountActionCommandTest extends TestCase
{
    /** @var AbstractGreenfeePercentageDiscountActionCommand */
    protected $actionCommand;

    protected function setUp()
    {
        $this->actionCommand = new class extends AbstractGreenfeePercentageDiscountActionCommand
        {
            public function getType(): string
            {
                return 'new_action_type';
            }
        };
    }

    public function testValidateConfiguration()
    {
        $this->actionCommand->validateConfiguration(['percentage_coefficient' => 0.1]);

        $this->actionCommand->validateConfiguration(['percentage_coefficient' => 1]);

        $this->expectException(InvalidConfigurablePromotionException::class);
        $this->actionCommand->validateConfiguration(['percentage_coefficient' => 1.1]);

        $this->expectException(InvalidConfigurablePromotionException::class);
        $this->actionCommand->validateConfiguration(['percentage_coefficient' => 0]);

        $this->expectException(InvalidConfigurablePromotionException::class);
        $this->actionCommand->validateConfiguration(['wrong_key' => 0]);
    }
}