<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Extension;

use Elastica\Query;

interface QueryExtensionInterface
{
    /**
     * @param Query $query
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     *
     * @return Query
     */
    public function applyToQuery(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query;
}