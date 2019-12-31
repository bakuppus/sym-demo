<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use Elastica\Query;
use Elastica\Script\Script;
use Elastica\Script\ScriptFields;

final class CalculateDistanceFilter extends AbstractFilter
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
            if (false === isset($propertyData['lat']) || false === isset($propertyData['lat'])) {
                continue;
            }

            $this->addQuery($query, $propertyData);

            break;
        }

        return $query;
    }

    public function addQuery(Query $query, array $propertyData): void
    {
        $script = new Script("doc['lonlat'].arcDistance(params.lat,params.lon)", [
            'lat' => (float) $propertyData['lat'],
            'lon' => (float) $propertyData['lon'],
        ], 'painless');

        $scriptFields = new ScriptFields();
        $scriptFields->addScript('distance', $script);

        $query->setScriptFields($scriptFields);
        $query->setStoredFields(['_source']);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . "[lat]"] = [
                'property' => $property,
                'type' => 'boolean',
                'required' => false,
            ];

            $description[$property . "[lon]"] = [
                'property' => $property,
                'type' => 'boolean',
                'required' => false,
            ];
        }

        return $description;
    }
}
