<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Tests\Unit\Infrastructure\Shared\Command\CommandAwareTest;

final class DummyCommandWithMultipleBinding extends CommandAwareTest
{
    /**
     * @var int|DummyEntity
     *
     * @CommandBind(targetEntity="App\Tests\Unit\Infrastructure\Shared\Command\Binding\DummyEntity")
     */
    public $dummy1 = 1;

    /**
     * @var int|DummyEntity2
     *
     * @CommandBind(targetEntity="App\Tests\Unit\Infrastructure\Shared\Command\Binding\DummyEntity2")
     */
    public $dummy2 = 2;

    /**
     * @var int|DummyEntity3
     *
     * @CommandBind(targetEntity="App\Tests\Unit\Infrastructure\Shared\Command\Binding\DummyEntity3")
     */
    public $dummy3 = 3;
}
