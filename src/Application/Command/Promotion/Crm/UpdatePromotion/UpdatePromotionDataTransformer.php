<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdatePromotion;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\Promotion;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;

final class UpdatePromotionDataTransformer implements DataTransformerInterface
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
        if ($data instanceof Promotion) {
            return false;
        }

        return Promotion::class === $to && UpdatePromotionCommand::class === $context['input']['class'];
    }
}
