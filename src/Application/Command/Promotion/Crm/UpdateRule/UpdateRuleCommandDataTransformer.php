<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateRule;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\PromotionRule;

class UpdateRuleCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof PromotionRule) {
            return false;
        }

        return PromotionRule::class === $to && UpdateRuleCommand::class === $context['input']['class'];
    }
}
