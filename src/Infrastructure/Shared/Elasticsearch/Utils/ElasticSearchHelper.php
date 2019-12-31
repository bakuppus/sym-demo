<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Utils;

trait ElasticSearchHelper
{
    public function isDistanceUnit(?string $unit): bool
    {
        return in_array($unit, ['mi', 'miles', 'yd', 'yards', 'ft', 'feet', 'in', 'inch', 'km', 'kilometers', 'm', 'meters', 'cm', 'centimeters', 'mm', 'millimeters', 'NM', 'nmi', 'nauticalmiles',], true);
    }

    public function isSortDirection(?string $direction): bool
    {
        return in_array($direction, ['asc', 'desc'], true);
    }
}
