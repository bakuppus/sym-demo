<?php

declare(strict_types=1);

namespace App\Domain\TeeTime;

/**
 * @method int getPriority()
 */
trait PriorityTrait
{
    public function getLowerPriorityValue(): int
    {
        return $this->getPriority() - 1;
    }

    public function getHigherPriorityValue(): int
    {
        return $this->getPriority() + 1;
    }
}
