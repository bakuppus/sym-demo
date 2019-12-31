<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\AddNewMembership;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Domain\Club\Club;

final class AddNewMembershipCommandDataTransformer implements DataTransformerInterface
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

        return Club::class === $to && AddNewMembershipCommand::class === $context['input']['class'];
    }
}
