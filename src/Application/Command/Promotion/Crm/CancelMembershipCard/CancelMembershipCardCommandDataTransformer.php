<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\CancelMembershipCard;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;

final class CancelMembershipCardCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof MembershipCard) {
            return false;
        }

        return MembershipCard::class === $to && CancelMembershipCardCommand::class === $context['input']['class'];
    }
}
