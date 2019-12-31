<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Order;

use App\Domain\Order\Core\OrderItemInterface;
use App\Domain\Order\Order;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testAddItem(): void
    {
        $orderItem_1 = $this->createMock(OrderItemInterface::class);
        $orderItem_1->method('getTotal')->willReturn(100);
        $orderItem_1->method('getQuantity')->willReturn(2);

        $orderItem_2 = $this->createMock(OrderItemInterface::class);
        $orderItem_2->method('getTotal')->willReturn(50);
        $orderItem_2->method('getQuantity')->willReturn(1);

        $order = new Order();
        $order->addItem($orderItem_1);
        $order->addItem($orderItem_1);

        $this->assertEquals(100, $orderItem_1->getTotal());
        $this->assertEquals(50, $orderItem_2->getTotal());
        $this->assertEquals(100, $order->getTotal());
        $this->assertEquals(100, $order->getItemsTotal());

        $order->addItem($orderItem_2);

        $this->assertEquals(150, $order->getTotal());
        $this->assertEquals(150, $order->getItemsTotal());
        $this->assertEquals(3, $order->getTotalQuantity());
        $this->assertTrue(false === $order->isEmpty());
        $this->assertTrue(true === $order->hasItem($orderItem_1));
        $this->assertTrue(true === $order->hasItem($orderItem_2));
        $this->assertInstanceOf(Collection::class, $order->getItems());
        $this->assertContainsOnlyInstancesOf(OrderItemInterface::class, $order->getItems());

        $order->clearItems();

        $this->assertEquals(0, $order->getTotal());
        $this->assertEquals(0, $order->getItemsTotal());
        $this->assertEquals(0, $order->getTotalQuantity());
        $this->assertTrue(true === $order->isEmpty());
    }

    public function testRemoveItem(): void
    {
        $orderItem_1 = $this->createMock(OrderItemInterface::class);
        $orderItem_1->method('getTotal')->willReturn(100);

        $orderItem_2 = $this->createMock(OrderItemInterface::class);
        $orderItem_2->method('getTotal')->willReturn(50);

        $orderItem_3 = $this->createMock(OrderItemInterface::class);
        $orderItem_3->method('getTotal')->willReturn(70);

        $order = new Order();
        $order->addItem($orderItem_1);
        $order->addItem($orderItem_2);
        $order->removeItem($orderItem_1);
        $order->removeItem($orderItem_3);

        $this->assertEquals(50, $order->getTotal());
        $this->assertEquals(50, $order->getItemsTotal());

        $order->removeItem($orderItem_2);

        $this->assertEquals(0, $order->getTotal());
        $this->assertEquals(0, $order->getItemsTotal());
        $this->assertTrue(true === $order->isEmpty());
        $this->assertTrue(false === $order->hasItem($orderItem_1));
        $this->assertInstanceOf(Collection::class, $order->getItems());
    }
}
