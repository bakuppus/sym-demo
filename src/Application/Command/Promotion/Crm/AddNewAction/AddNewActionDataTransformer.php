<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewAction;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\Promotion;
use App\Domain\Promotion\PromotionAction;

class AddNewActionDataTransformer implements DataTransformerInterface
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
        if ($data instanceof PromotionAction) {
            return false;
        }

        return Promotion::class === $to && AddNewActionCommand::class === $context['input']['class'];
    }
}