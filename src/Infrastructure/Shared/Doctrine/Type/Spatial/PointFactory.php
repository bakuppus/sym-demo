<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Doctrine\Type\Spatial;

final class PointFactory
{
    public function create(?float $longitude, ?float $latitude): ?Point
    {
        if (null == $longitude || null === $latitude) {
            return null;
        }

        return new Point($longitude, $latitude);
    }
}
