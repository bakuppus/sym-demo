<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Doctrine;

use App\Domain\Shared\ValueObject\Origin;

interface OriginAwareInterface
{
    public function getOrigin(): Origin;
}
