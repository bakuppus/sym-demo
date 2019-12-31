<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\SyncMembership;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;

final class SyncMembershipCommandDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param CommandPopulatableInterface $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $object->populate($context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Membership) {
            return false;
        }

        return Membership::class === $to && SyncMembershipCommand::class === $context['input']['class'];
    }
}
