<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Extension;

use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Api\IdentifierExtractorInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Exception\NonUniqueIdentifierException;
use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use ApiPlatform\Core\Bridge\Elasticsearch\Util\FieldDatatypeTrait;
use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Elastica\Query;
use function is_array;
use function is_int;

final class DefaultSortExtension implements QueryExtensionInterface
{
    use FieldDatatypeTrait;

    /** @var ResourceMetadataFactoryInterface */
    private $resourceMetadataFactory;

    /** @var IdentifierExtractorInterface */
    private $identifierExtractor;

    /** @var PropertyMetadataFactoryInterface */
    private $propertyMetadataFactory;

    /** @var ResourceClassResolverInterface */
    private $resourceClassResolver;

    /** @var InnerFieldsNameConverter */
    private $nameConverter;

    /** @var string|null */
    private $defaultDirection;

    public function __construct(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        IdentifierExtractorInterface $identifierExtractor,
        PropertyMetadataFactoryInterface $propertyMetadataFactory,
        ResourceClassResolverInterface $resourceClassResolver,
        InnerFieldsNameConverter $nameConverter,
        ?string $defaultDirection = null
    ) {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->identifierExtractor = $identifierExtractor;
        $this->propertyMetadataFactory = $propertyMetadataFactory;
        $this->resourceClassResolver = $resourceClassResolver;
        $this->nameConverter = $nameConverter;
        $this->defaultDirection = $defaultDirection;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ResourceClassNotFoundException
     * @throws NonUniqueIdentifierException
     */
    public function applyToQuery(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        $orders = [];

        if (
            null !== ($defaultOrder = $this->resourceMetadataFactory->create($resourceClass)->getAttribute('order'))
            && is_array($defaultOrder)
        ) {
            foreach ($defaultOrder as $property => $direction) {
                if (is_int($property)) {
                    $property = $direction;
                    $direction = 'asc';
                }

                $orders[] = $this->getOrder($resourceClass, $property, $direction);
            }
        } elseif (null !== $this->defaultDirection) {
// TODO: Fix later :) @SWAT
//            if (0 === (int)isset($context['filters'])) {
//                $orders[] = $this->getOrder(
//                    $resourceClass,
//                    $this->identifierExtractor->getIdentifierFromResourceClass($resourceClass),
//                    $this->defaultDirection
//                );
//            }
        }

        foreach ($orders as $sort) {
            $query->addSort($sort);
        }

        return $query;
    }

    private function getOrder(string $resourceClass, string $property, string $direction): array
    {
        $order = ['order' => strtolower($direction)];

        if (null !== $nestedPath = $this->getNestedFieldPath($resourceClass, $property)) {
            $nestedPath = null === $this->nameConverter ? $nestedPath : $this->nameConverter->normalize($nestedPath,
                $resourceClass);
            $order['nested'] = ['path' => $nestedPath];
        }

        $property = null === $this->nameConverter ? $property : $this->nameConverter->normalize($property,
            $resourceClass);

        return [$property => $order];
    }
}
