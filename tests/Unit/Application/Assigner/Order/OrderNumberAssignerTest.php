<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Assigner\Order;

use App\Application\Assigner\Order\Number\OrderNumberAssigner;
use App\Domain\Order\Order;
use App\Infrastructure\Shared\Generator\Barcode\BarcodeGenerator;
use PHPUnit\Framework\TestCase;

final class OrderNumberAssignerTest extends TestCase
{
    public function testAssignEmptyNumber(): void
    {
        $order = new Order();
        $assigner = new OrderNumberAssigner(new BarcodeGenerator());
        $assigner->assignNumber($order);

        $this->assertNotEmpty($order->getNumber());
    }

    public function testAssignNotEmptyNumber(): void
    {
        $generator = new BarcodeGenerator();

        $order = new Order();
        $order->setNumber($generator->ean13());

        $assigner = new OrderNumberAssigner($generator);
        $assigner->assignNumber($order);

        $this->assertNotEmpty($order->getNumber());
    }
}
