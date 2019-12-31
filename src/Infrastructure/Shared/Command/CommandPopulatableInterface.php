<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command;

interface CommandPopulatableInterface
{
    public function populate(array $context): void;

    public function getObjectToPopulate(): object;
}
