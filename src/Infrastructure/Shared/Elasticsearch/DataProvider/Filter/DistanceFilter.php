<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use Elastica\Query;

final class DistanceFilter extends AbstractFilter
{
    /**
     * {@inheritDoc}
     */
    public function apply(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        foreach ($this->getProperties($resourceClass) as $property) {
            if (false === isset($context['filters'][$property])) {
                continue;
            }
            $propertyData = $context['filters'][$property];
            $location = ['lat' => $propertyData['lat'], 'lon' => $propertyData['lon']];
            $distance = $propertyData['distance'];

            $this->stackMust($query, new Query\GeoDistance($property, $location, $distance));
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . '[lat]'] = [
                'property' => $property,
                'type' => 'float',
                'required' => false,
                'swagger' => [
                    'description' => 'Latitude',
                ],
            ];

            $description[$property . '[lon]'] = [
                'property' => $property,
                'type' => 'float',
                'required' => false,
                'swagger' => [
                    'description' => 'Longitude',
                ],
            ];

            $description[$property . '[distance]'] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'description' => 'Visible courses in radius, example: 100m or 1500km',
                ],
            ];
        }

        return $description;
    }
}
