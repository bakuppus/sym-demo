<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;

trait CommandPopulatableTrait
{
    /** @var object */
    public $objectToPopulate;

    public function populate(array $context): void
    {
        $this->objectToPopulate = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
    }
}
