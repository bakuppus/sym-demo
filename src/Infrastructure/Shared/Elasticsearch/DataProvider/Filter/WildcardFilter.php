<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use Elastica\Query;
use Elastica\Query\Nested;
use Elastica\Query\Wildcard;

final class WildcardFilter extends AbstractFilter
{
    public function apply(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        $properties = $this->getNormalizedProperties($resourceClass, $context);
        foreach ($properties as $fieldName => $propertyData) {
            $this->addQuery($resourceClass, $query, $fieldName, $propertyData);
        }

        return $query;
    }

    protected function addQuery(string $resourceClass, Query $query, string $fieldName, string $property): void
    {
        $addQuery = new Wildcard($this->nameConverter->normalize($fieldName), '*' . $property . '*');
        if (true === $this->isNestedField($resourceClass, $fieldName)) {
            $nestedQuery = new Nested();
            $nestedPath = $this->nameConverter->normalize(
                $this->getNestedFieldPath($resourceClass, $fieldName)
            );
            $nestedQuery->setPath($nestedPath);
            $addQuery = $nestedQuery->setQuery($addQuery);
        }

        $this->stackMust($query, $addQuery);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }

    public function getNormalizedProperties(string $resourceClass, array $context): array
    {
        $normalizedProperties = [];
        foreach ($this->getProperties($resourceClass) as $propertyName) {
            if (false === isset($context['filters'][$propertyName])) {
                continue;
            }
            $propertyData = $context['filters'][$propertyName];

            $normalizedProperties[$propertyName] = $propertyData;
        }

        return $normalizedProperties;
    }
}
