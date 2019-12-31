<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Promotion;

use App\Domain\Promotion\Promotion;
use PHPUnit\Framework\TestCase;

final class PromotionTest extends TestCase
{
    public function testCode(): void
    {
        $promotion = new Promotion();
        $this->assertNull($promotion->getCode());
        $this->assertInstanceOf(Promotion::class, $promotion->setCode('text'));
        $this->assertEquals('text', $promotion->getCode());
    }

    public function testName(): void
    {
        $promotion = new Promotion();
        $this->assertNull($promotion->getName());
        $this->assertInstanceOf(Promotion::class, $promotion->setName('text'));
        $this->assertEquals('text', $promotion->getName());
    }

    public function testDescription(): void
    {
        $promotion = new Promotion();
        $this->assertNull($promotion->getDescription());
        $this->assertInstanceOf(Promotion::class, $promotion->setDescription('text'));
        $this->assertEquals('text', $promotion->getDescription());
    }

    public function testPriority(): void
    {
        $promotion = new Promotion();
        $this->assertNull($promotion->getPriority());
        $this->assertInstanceOf(Promotion::class, $promotion->setPriority(10));
        $this->assertEquals(10, $promotion->getPriority());
        $this->assertInstanceOf(Promotion::class, $promotion->setPriority(null));
        $this->assertEquals(-1, $promotion->getPriority());
    }

    public function testUsageLimit(): void
    {
        $promotion = new Promotion();
        $this->assertNull($promotion->getUsageLimit());
        $this->assertInstanceOf(Promotion::class, $promotion->setUsageLimit(100));
        $this->assertEquals(100, $promotion->getUsageLimit());
        $this->assertInstanceOf(Promotion::class, $promotion->setUsageLimit(null));
        $this->assertNull($promotion->getUsageLimit());
    }

    public function testUsed(): void
    {
        $promotion = new Promotion();
        $this->assertEquals(0, $promotion->getUsed());
        $this->assertInstanceOf(Promotion::class, $promotion->setUsed(10));
        $this->assertEquals(10, $promotion->getUsed());
        $this->assertInstanceOf(Promotion::class, $promotion->incrementUsed());
        $this->assertEquals(11, $promotion->getUsed());
        $this->assertInstanceOf(Promotion::class, $promotion->decrementUsed());
        $this->assertInstanceOf(Promotion::class, $promotion->decrementUsed());
        $this->assertEquals(9, $promotion->getUsed());
    }
}