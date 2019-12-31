<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateAction;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\PromotionAction;

/**
 * Class UpdateActionCommandDataTransformer
 * @package App\Application\Command\Promotion\Crm\UpdateAction
 */
final class UpdateActionCommandDataTransformer implements DataTransformerInterface
{
    /**
     * @param object $object
     * @param string $to
     * @param array $context
     *
     * @return object
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
        if ($data instanceof PromotionAction) {
            return false;
        }

        return PromotionAction::class === $to && UpdateActionCommand::class === $context['input']['class'];
    }
}