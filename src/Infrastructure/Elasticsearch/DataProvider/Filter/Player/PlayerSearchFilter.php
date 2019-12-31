<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\DisMax;
use Elastica\Query\MultiMatch;
use Elastica\Query\Wildcard;

class PlayerSearchFilter extends AbstractFilter
{
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
            $searchField = (string) $context['filters'][$property];

            $this->addQuery($query, $searchField);
        }

        return $query;
    }

    public function addQuery(Query $query, string $searchField)
    {
        $searchField = trim($searchField);
        $disMax = new DisMax();

        $multiMatch = (new MultiMatch())
            ->setQuery($searchField)
            ->setOperator('AND')
            ->setFields([
                'first_name^10',
                'last_name^10',
                'email^10',
                'golf_id^10',
                'phone^10',
                'search_field',
            ])
            ->setType('cross_fields');
        $disMax->addQuery($multiMatch);

        $wildCard = new Wildcard('search_field.wildcard', sprintf('*%s*', $searchField));
        $disMax->addQuery($wildCard);

        $this->stackMust($query, $disMax);
    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'type' => 'string',
                ],
            ];
        }

        return $description;
    }
}
