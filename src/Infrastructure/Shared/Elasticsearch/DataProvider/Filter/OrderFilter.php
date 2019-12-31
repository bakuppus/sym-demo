<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use Elastica\Query;

final class OrderFilter extends AbstractFilter implements FilterInterface
{
    private $orderParameterName;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        PropertyMetadataFactoryInterface $propertyMetadataFactory,
        ResourceClassResolverInterface $resourceClassResolver,
        ?InnerFieldsNameConverter $nameConverter = null,
        string $orderParameterName = 'order',
        ?array $properties = null
    ) {
        parent::__construct($propertyNameCollectionFactory, $propertyMetadataFactory, $resourceClassResolver,
            $nameConverter, $properties);

        $this->orderParameterName = $orderParameterName;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        $properties = array_intersect_key($context['filters'][$this->orderParameterName] ?? [], $this->properties);
        if (true === empty($properties)) {
            return $query;
        }

        foreach ($properties as $property => $direction) {
            $order = ['order' => strtolower($direction)];

            if (null !== $nestedPath = $this->getNestedFieldPath($resourceClass, $property)) {
                $nestedPath = null === $this->nameConverter ? $nestedPath : $this->nameConverter->normalize($nestedPath,
                    $resourceClass);
                $order['nested'] = ['path' => $nestedPath];
            }

            $property = null === $this->nameConverter ? $property : $this->nameConverter->normalize($property,
                $resourceClass);

            $query->addSort([$property => $order]);
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
            [$type] = $this->getMetadata($resourceClass, $property);

            if (null === $type) {
                continue;
            }

            $description["$this->orderParameterName[$property]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }
}