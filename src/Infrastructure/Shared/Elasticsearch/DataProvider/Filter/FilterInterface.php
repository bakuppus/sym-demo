<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use ApiPlatform\Core\Api\FilterInterface as BaseFilterInterface;
use Elastica\Query;

interface FilterInterface extends BaseFilterInterface
{
    /**
     * @param Query $query
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     *
     * @return Query
     */
    public function apply(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query;
}