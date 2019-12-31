<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\CreateClub;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Domain\Club\Club;

final class CreateClubCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof Club) {
            return false;
        }

        return Club::class === $to && CreateClubCommand::class === $context['input']['class'];
    }
}
