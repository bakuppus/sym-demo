<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Command;

use App\Infrastructure\Shared\Command\CommandAwareInterface;
use stdClass;

abstract class CommandAwareTest implements CommandAwareInterface
{
    public function getResource(): object
    {
        return new stdClass();
    }
}
