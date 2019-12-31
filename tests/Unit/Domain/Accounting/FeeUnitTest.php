<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Accounting;

use App\Domain\Accounting\FeeUnit;
use PHPUnit\Framework\TestCase;

final class FeeUnitTest extends TestCase
{
    public function testName(): void
    {
        $feeUnit = new FeeUnit();
        $this->assertNull($feeUnit->getName());
        $this->assertInstanceOf(FeeUnit::class, $feeUnit->setName('some name'));
        $this->assertEquals('some name', $feeUnit->getName());
        $this->assertInstanceOf(FeeUnit::class, $feeUnit->setName('another name'));
        $this->assertEquals('another name', $feeUnit->getName());
    }
}
