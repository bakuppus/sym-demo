<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\UpdateClub;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Domain\Club\Club;

final class UpdateClubCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof Club) {
            return false;
        }

        if (false === isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            return false;
        }

        return Club::class === $to && UpdateClubCommand::class === $context['input']['class'];
    }
}
