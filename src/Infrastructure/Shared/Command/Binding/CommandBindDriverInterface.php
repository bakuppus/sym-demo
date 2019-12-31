<?php

namespace App\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Doctrine\ORM\EntityNotFoundException;

interface CommandBindDriverInterface
{
    /**
     * @param CommandAwareInterface $command
     *
     * @return void
     * @throws EntityNotFoundException
     */
    public function bind(CommandAwareInterface $command): void;
}
