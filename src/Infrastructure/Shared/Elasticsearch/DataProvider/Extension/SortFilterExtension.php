<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Extension;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\FilterInterface;
use Elastica\Query;
use Psr\Container\ContainerInterface;

final class SortFilterExtension implements QueryExtensionInterface
{
    private $resourceMetadataFactory;

    private $filterLocator;

    public function __construct(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        ContainerInterface $filterLocator
    ) {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->filterLocator = $filterLocator;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ResourceClassNotFoundException
     */
    public function applyToQuery(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
        $resourceFilters = $resourceMetadata->getCollectionOperationAttribute($operationName, 'filters', [], true);

        if (true === empty($resourceFilters)) {
            return $query;
        }

        $context['filters'] = $context['filters'] ?? [];

        foreach ($resourceFilters as $filterId) {
            if (false === $this->filterLocator->has($filterId)) {
                continue;
            }

            $filter = $this->filterLocator->get($filterId);
            /** @var FilterInterface $filter */
            if (false === $filter instanceof FilterInterface) {
                continue;
            }

            $query = $filter->apply($query, $resourceClass, $operationName, $context);
        }

        return $query;
    }
}