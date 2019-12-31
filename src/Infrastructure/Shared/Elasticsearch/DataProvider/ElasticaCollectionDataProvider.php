<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use App\Infrastructure\Shared\Elasticsearch\Bridge\HybridPaginatorToPagerfanta;
use App\Infrastructure\Shared\Elasticsearch\Bridge\Paginator;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Extension\QueryExtensionInterface;
use Doctrine\Common\Inflector\Inflector;
use Elastica\Query;
use FOS\ElasticaBundle\Manager\RepositoryManager;
use FOS\ElasticaBundle\Repository;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RuntimeException;

final class ElasticaCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var RepositoryManager
     */
    private $repositoryManager;

    /**
     * @var ResourceMetadataFactoryInterface
     */
    private $resourceMetadataFactory;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var iterable
     */
    private $collectionExtensions;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param RepositoryManager $repositoryManager
     * @param ResourceMetadataFactoryInterface $resourceMetadataFactory
     * @param Pagination $pagination
     * @param QueryExtensionInterface[] $collectionExtensions
     */
    public function __construct(
        RepositoryManager $repositoryManager,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        Pagination $pagination,
        iterable $collectionExtensions = [],
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->pagination = $pagination;
        $this->collectionExtensions = $collectionExtensions;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     * @throws ResourceClassNotFoundException
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $resourceShortName = $this->resourceMetadataFactory->create($resourceClass)->getShortName();
        $index = Inflector::tableize($resourceShortName);

        $repository = $this->repositoryManager->getRepository($index . '/_doc');

        $limit = $this->pagination->getLimit($resourceClass, $operationName, $context);
        $page = $this->pagination->getPage($context);

        $query = new Query();

        foreach ($this->collectionExtensions as $collectionExtension) {
            $query = $collectionExtension->applyToQuery($query, $resourceClass, $operationName, $context);
        }

        $hybridPaginator = $repository->createHybridPaginatorAdapter($query);
        $pagerfanta = new Pagerfanta(new HybridPaginatorToPagerfanta($hybridPaginator, $this->eventDispatcher));
        try {
            $pagerfanta
                ->setCurrentPage($page)
                ->setMaxPerPage($limit);
        } catch (OutOfRangeCurrentPageException $exception) {
            return [];
        }

        return new Paginator($pagerfanta);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        try {
            $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
            $collection = $resourceMetadata->getCollectionOperationAttribute(
                $operationName,
                'elasticsearch',
                true,
                true
            );
            if (false === $collection) {
                return false;
            }

            $this->getElasticaRepository($resourceClass);
        } catch (ResourceClassNotFoundException | RuntimeException $e) {
            return false;
        }

        return true;
    }

    /**
     * @throws ResourceClassNotFoundException
     * @throws RuntimeException If there is no index for resource
     */
    private function getElasticaRepository(string $resourceClass): Repository
    {
        $resourceShortName = $this->resourceMetadataFactory->create($resourceClass)->getShortName();
        $index = Inflector::tableize($resourceShortName);

        return $this->repositoryManager->getRepository($index . '/_doc');
    }
}
