<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command;

interface CommandAwareInterface
{
    public function getResource(): object;
}
