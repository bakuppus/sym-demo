<?php

declare(strict_types=1);

namespace App\Application\Command\Order\UpdateOrder;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;

final class UpdateOrderCommandDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     * @param CommandPopulatableInterface $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $object->populate($context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Order) {
            return false;
        }

        if (false === isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            return false;
        }

        return Order::class === $to && UpdateOrderCommand::class === $context['input']['class'];
    }
}
