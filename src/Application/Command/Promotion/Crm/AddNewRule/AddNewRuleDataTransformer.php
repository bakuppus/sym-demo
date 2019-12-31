<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewRule;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\Promotion;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;

final class AddNewRuleDataTransformer implements DataTransformerInterface
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

    /**
     * @param array|object $data
     * @param string $to
     * @param array $context
     *
     * @return bool
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Promotion) {
            return false;
        }

        return Promotion::class === $to && AddNewRuleCommand::class === $context['input']['class'];
    }
}
