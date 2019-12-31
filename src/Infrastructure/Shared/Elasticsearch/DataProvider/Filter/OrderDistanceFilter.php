<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use App\Infrastructure\Shared\Elasticsearch\Utils\ElasticSearchHelper;
use Elastica\Query;

final class OrderDistanceFilter extends AbstractFilter
{
    use ElasticSearchHelper;

    private $orderParameterName;

    /**
     * {@inheritdoc}
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

        foreach ($properties as $property => $order) {
            [$type] = $this->getMetadata($resourceClass, $property);

            if (null === $type) {
                continue;
            }

            $direction = $order['order'] ?? null;
            $unit = $order['unit'] ?? null;

            if (false === $this->isSortDirection($direction)) {
                continue;
            }

            if (false === $this->isDistanceUnit($unit)) {
                continue;
            }

            $property = null === $this->nameConverter ? $property : $this->nameConverter->normalize($property, $resourceClass, null, $context);

            $query->addSort([
                '_geo_distance' => [
                    $property => [
                        'lat' => $order['lat'],
                        'lon' => $order['lon'],
                    ],
                    'unit' => $order['unit'],
                    'order' => $order['order'],
                ],
            ]);
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

            if (!$type) {
                continue;
            }

            $description["$this->orderParameterName[$property][lat]"] = [
                'property' => $property,
                'type' => 'float',
                'required' => false,
            ];

            $description["$this->orderParameterName[$property][lon]"] = [
                'property' => $property,
                'type' => 'float',
                'required' => false,
            ];

            $description["$this->orderParameterName[$property][unit]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];

            $description["$this->orderParameterName[$property][order]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }
}
