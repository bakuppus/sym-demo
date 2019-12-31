<?php

namespace App\Infrastructure\Shared\Command;

/**
 * @experimental TBD just an concept
 */
interface DeleteCommandWorkflowInterface
{
    public function getWorkflow(): string;

    public function removeTransitionName(): string;
}
