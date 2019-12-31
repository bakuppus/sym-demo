<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use App\Infrastructure\Shared\Elasticsearch\Exceptions\ElasticInvalidArgumentException;
use App\Infrastructure\Shared\Elasticsearch\Factory\RangeFilterPropertyFactory;
use App\Infrastructure\Shared\Elasticsearch\Model\RangeFilterProperty;
use Elastica\Query;

final class RangeFilter extends AbstractFilter
{
    public const PARAMETER_GREATER_THAN = 'gt';
    public const PARAMETER_GREATER_THAN_OR_EQUAL = 'gte';
    public const PARAMETER_LESS_THAN = 'lt';
    public const PARAMETER_LESS_THAN_OR_EQUAL = 'lte';

    public const PREDICATES = [
        self::PARAMETER_GREATER_THAN,
        self::PARAMETER_GREATER_THAN_OR_EQUAL,
        self::PARAMETER_LESS_THAN,
        self::PARAMETER_LESS_THAN_OR_EQUAL,
    ];

    /** @var RangeFilterPropertyFactory|null */
    protected $rangeFilterPropertyFactory;

    public function __construct(
        PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        PropertyMetadataFactoryInterface $propertyMetadataFactory,
        ResourceClassResolverInterface $resourceClassResolver,
        ?InnerFieldsNameConverter $nameConverter = null,
        ?array $properties = null,
        ?RangeFilterPropertyFactory $rangeFilterPropertyFactory = null
    ) {
        parent::__construct($propertyNameCollectionFactory, $propertyMetadataFactory, $resourceClassResolver,
            $nameConverter, $properties);

        $this->rangeFilterPropertyFactory = $rangeFilterPropertyFactory;
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
        $properties = $this->getNormalizedProperties($resourceClass, $context);
        $this->validateProperties($properties);
        foreach ($properties as $property) {
            $this->addQuery($resourceClass, $query, $property);
        }

        return $query;
    }

    protected function addQuery(string $resourceClass, Query $query, RangeFilterProperty $property): Query
    {
        $addQuery = new Query\Range($this->nameConverter->normalize($property->getName()),[
            $property->getPredicate() => $property->getValue(),
        ]);
        if (true === $this->isNestedField($resourceClass, $property->getName())) {
            $nestedQuery = new Query\Nested();
            $nestedPath = $this->nameConverter->normalize(
                $this->getNestedFieldPath($resourceClass, $property->getName())
            );
            $nestedQuery->setPath($nestedPath);
            $addQuery = $nestedQuery->setQuery($addQuery);
        }

        return $this->stackMust($query, $addQuery);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . "[" . self::PARAMETER_GREATER_THAN . "]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
            $description[$property . "[" . self::PARAMETER_GREATER_THAN_OR_EQUAL . "]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
            $description[$property . "[" . self::PARAMETER_LESS_THAN . "]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
            $description[$property . "[" . self::PARAMETER_LESS_THAN_OR_EQUAL . "]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }

    public function validateProperties(array $properties): void
    {
        foreach ($properties as $property) {
            /** @var RangeFilterProperty $property */
            if (false === in_array($property->getPredicate(), self::PREDICATES)) {
                throw ElasticInvalidArgumentException::notValidPredicate($property->getName());
            }
        }

        $usageCount = [];
        foreach ($properties as $property) {
            /** @var RangeFilterProperty $property */
            switch ($property->getPredicate()) {
                case self::PARAMETER_LESS_THAN_OR_EQUAL:
                    $countPredicate = self::PARAMETER_LESS_THAN;
                    break;
                case self::PARAMETER_GREATER_THAN_OR_EQUAL:
                    $countPredicate = self::PARAMETER_GREATER_THAN;
                    break;
                default:
                    $countPredicate = $property->getPredicate();
                    break;
            }

            $predicateRow = & $usageCount[$property->getName()][$countPredicate];
            if (false === isset($predicateRow)) {
                $predicateRow = 0;
            }

            $predicateRow++;

            if ($predicateRow >= 2) {
                throw ElasticInvalidArgumentException::predicateLogicException(
                    $property->getName(),
                    $property->getPredicate()
                );
            }
        }
    }

    public function getNormalizedProperties(string $resourceClass, array $context): array
    {
        $normalizedProperties = [];
        foreach ($this->getProperties($resourceClass) as $propertyName) {
            if (false === isset($context['filters'][$propertyName])) {
                continue;
            }
            $propertyData = $context['filters'][$propertyName];

            if (false === is_array($propertyData)) {
                throw ElasticInvalidArgumentException::predicateRequired($propertyName);
            }

            $normalizedProperties = array_merge(
                $this->rangeFilterPropertyFactory->makeCollection($propertyData, $propertyName),
                $normalizedProperties
            );
        }

        return $normalizedProperties;
    }
}
