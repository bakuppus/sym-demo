<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Order\Crm\Workflow;

use App\Application\Command\Order\Workflow\SellOrderCommand;
use App\Application\Command\Order\Workflow\SellOrderCommandDataTransformer;
use App\Domain\Order\Order;
use App\Domain\Order\OrderBooking;
use App\Domain\Order\OrderMembership;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class SellOrderCommandDataTransformerTest extends TestCase
{
    public function testSupports(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->discriminatorMap = [
            'order' => Order::class,
            'order_booking' => OrderBooking::class,
            'order_membership' => OrderMembership::class,
        ];
        $manager->expects(self::once())->method('getClassMetadata')->willReturn($classMetadata);

        $transformer = new SellOrderCommandDataTransformer($manager);
        $context = ['input' => ['class' => SellOrderCommand::class]];
        $transformer->supportsTransformation(new SellOrderCommand(), OrderMembership::class, $context);
        $context = [AbstractNormalizer::OBJECT_TO_POPULATE => new OrderMembership()];
        $transformer->transform(new SellOrderCommand(), OrderMembership::class, $context);
    }
}
