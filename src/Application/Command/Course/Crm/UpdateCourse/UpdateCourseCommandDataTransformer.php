<?php

declare(strict_types=1);

namespace App\Application\Command\Course\Crm\UpdateCourse;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Domain\Course\Course;

final class UpdateCourseCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof Course) {
            return false;
        }

        if (false === isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            return false;
        }

        return Course::class === $to && UpdateCourseCommand::class === $context['input']['class'];
    }
}
