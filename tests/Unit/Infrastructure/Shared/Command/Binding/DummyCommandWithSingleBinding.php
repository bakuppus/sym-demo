<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Tests\Unit\Infrastructure\Shared\Command\CommandAwareTest;

final class DummyCommandWithSingleBinding extends CommandAwareTest
{
    /**
     * @var int|DummyEntity
     *
     * @CommandBind(targetEntity="App\Tests\Unit\Infrastructure\Shared\Command\Binding\DummyEntity")
     */
    public $dummy = 1;
}
