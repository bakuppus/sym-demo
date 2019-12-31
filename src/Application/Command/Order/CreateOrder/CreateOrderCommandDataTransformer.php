<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandAwareInterface;

final class CreateOrderCommandDataTransformer implements DataTransformerInterface
{
    /**
     * @param CommandAwareInterface $object
     * {@inheritDoc}
     */
    public function transform($object, string $to, array $context = [])
    {
        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Order) {
            return false;
        }

        return Order::class === $to && CreateOrderCommand::class === $context['input']['class'];
    }
}
