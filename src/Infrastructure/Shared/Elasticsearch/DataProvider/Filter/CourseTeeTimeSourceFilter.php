<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use App\Domain\Course\Course;
use Elastica\Query;

final class CourseTeeTimeSourceFilter extends AbstractFilter
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

            $this->addQuery($query, $propertyData);
        }

        return $query;
    }

    protected function addQuery(Query $query, string $propertyData): void
    {
        $queryString = new Query\QueryString(Course::SOURCE_SWEETSPOT);
        $queryString->setDefaultField('tee_time_source');

        if (true === $this->isGitRequired($propertyData)) {
            $this->stackMustNot($query, $queryString);
        } else {
            $this->stackMust($query, $queryString);
        }
    }

    protected function isGitRequired(string $propertyData): bool
    {
        return $propertyData === 'true';
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
                'type' => 'boolean',
                'required' => false,
                'swagger' => [
                    'type' => 'boolean',
                ],
            ];
        }

        return $description;
    }
}
