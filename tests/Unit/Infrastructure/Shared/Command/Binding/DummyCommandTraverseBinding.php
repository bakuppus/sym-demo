<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Tests\Unit\Infrastructure\Shared\Command\CommandAwareTest;

final class DummyCommandTraverseBinding extends CommandAwareTest
{
    /**
     * @var DummyCommandWithSingleBinding
     *
     * @CommandBind(isTraverse=true)
     */
    public $traverse;

    /**
     * @var DummyCommandWithSingleBinding[]
     *
     * @CommandBind(isTraverse=true)
     */
    public $collection = [];
}
