<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Factory;

use App\Infrastructure\Shared\Elasticsearch\Model\RangeFilterProperty;

class RangeFilterPropertyFactory
{
    public function makeCollection(array $data, string $propertyName): array
    {
        $properties = [];
        foreach ($data as $predicate => $value) {
            $property = new RangeFilterProperty();
            $property->setName($propertyName);
            $property->setPredicate($predicate);
            $property->setValue($value);

            $properties[] = $property;
        }

        return $properties;
    }
}
