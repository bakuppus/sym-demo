<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter;

use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use ApiPlatform\Core\Bridge\Elasticsearch\Util\FieldDatatypeTrait;
use ApiPlatform\Core\Exception\PropertyNotFoundException;
use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Symfony\Component\PropertyInfo\Type;

abstract class AbstractFilter implements FilterInterface
{
    use FieldDatatypeTrait { getNestedFieldPath as protected; isNestedField as protected; }

    /** @var array|null */
    protected $properties;

    /** @var PropertyNameCollectionFactoryInterface */
    protected $propertyNameCollectionFactory;

    /** @var InnerFieldsNameConverter|null */
    protected $nameConverter;

    public function __construct(
        PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        PropertyMetadataFactoryInterface $propertyMetadataFactory,
        ResourceClassResolverInterface $resourceClassResolver,
        ?InnerFieldsNameConverter $nameConverter = null,
        ?array $properties = null
    ) {
        $this->propertyNameCollectionFactory = $propertyNameCollectionFactory;
        $this->propertyMetadataFactory = $propertyMetadataFactory;
        $this->resourceClassResolver = $resourceClassResolver;
        $this->nameConverter = $nameConverter;
        $this->properties = $properties;
    }

    /**
     * Gets all enabled properties for the given resource class.
     */
    protected function getProperties(string $resourceClass): \Traversable
    {
        if (null !== $this->properties) {
            return yield from array_keys($this->properties);
        }

        try {
            yield from $this->propertyNameCollectionFactory->create($resourceClass);
        } catch (ResourceClassNotFoundException $e) {
        }
    }

    /**
     * Is the given property enabled?
     */
    protected function hasProperty(string $resourceClass, string $property): bool
    {
        return \in_array($property, iterator_to_array($this->getProperties($resourceClass)), true);
    }

    /**
     * Gets info about the decomposed given property for the given resource class.
     *
     * Returns an array with the following info as values:
     *   - the {@see Type} of the decomposed given property
     *   - is the decomposed given property an association?
     *   - the resource class of the decomposed given property
     *   - the property name of the decomposed given property
     */
    protected function getMetadata(string $resourceClass, string $property): array
    {
        $noop = [null, null, null, null];

        if (!$this->hasProperty($resourceClass, $property)) {
            return $noop;
        }

        $properties = explode('.', $property);
        $totalProperties = \count($properties);
        $currentResourceClass = $resourceClass;
        $hasAssociation = false;
        $currentProperty = null;
        $type = null;

        foreach ($properties as $index => $currentProperty) {
            try {
                $propertyMetadata = $this->propertyMetadataFactory->create($currentResourceClass, $currentProperty);
            } catch (PropertyNotFoundException $e) {
                return $noop;
            }

            if (null === $type = $propertyMetadata->getType()) {
                return $noop;
            }

            ++$index;
            $builtinType = $type->getBuiltinType();

            if (Type::BUILTIN_TYPE_OBJECT !== $builtinType && Type::BUILTIN_TYPE_ARRAY !== $builtinType) {
                if ($totalProperties === $index) {
                    break;
                }

                return $noop;
            }

            if ($type->isCollection() && null === $type = $type->getCollectionValueType()) {
                return $noop;
            }

            if (Type::BUILTIN_TYPE_ARRAY === $builtinType && Type::BUILTIN_TYPE_OBJECT !== $type->getBuiltinType()) {
                if ($totalProperties === $index) {
                    break;
                }

                return $noop;
            }

            if (null === $className = $type->getClassName()) {
                return $noop;
            }

            if ($isResourceClass = $this->resourceClassResolver->isResourceClass($className)) {
                $currentResourceClass = $className;
            } elseif ($totalProperties !== $index) {
                return $noop;
            }

            $hasAssociation = $totalProperties === $index && $isResourceClass;
        }

        return [$type, $hasAssociation, $currentResourceClass, $currentProperty];
    }

    public function stackMust(Query $originalQuery, AbstractQuery $addQuery): Query
    {
        if (true === $originalQuery->hasParam('query')) {
            if ($originalQuery->getQuery() instanceof BoolQuery) {
                $originalQuery->getQuery()->addMust($addQuery);
            } else {
                $stackQuery = new BoolQuery();
                $stackQuery->addMust($originalQuery->getQuery());
                $stackQuery->addMust($addQuery);

                $originalQuery->setQuery($stackQuery);
            }
        } else {
            $stackQuery = new BoolQuery();
            $stackQuery->addMust($addQuery);

            $originalQuery->setQuery($stackQuery);
        }

        return $originalQuery;
    }

    public function stackMustNot(Query $originalQuery, AbstractQuery $addQuery): Query
    {
        if (true === $originalQuery->hasParam('query')) {
            if ($originalQuery->getQuery() instanceof BoolQuery) {
                $originalQuery->getQuery()->addMustNot($addQuery);
            } else {
                $stackQuery = new BoolQuery();
                $stackQuery->addMust($originalQuery->getQuery());
                $stackQuery->addMustNot($addQuery);

                $originalQuery->setQuery($stackQuery);
            }
        } else {
            $stackQuery = new BoolQuery();
            $stackQuery->addMustNot($addQuery);

            $originalQuery->setQuery($stackQuery);
        }

        return $originalQuery;
    }

    public function getNestedFieldPath(string $resourceClass, string $property): ?string
    {
        $properties = explode('.', $property);
        $currentProperty = array_shift($properties);

        if (!$properties) {
            return null;
        }

        try {
            $propertyMetadata = $this->propertyMetadataFactory->create($resourceClass, $currentProperty);
        } catch (PropertyNotFoundException $e) {
            return null;
        }

        if (null === $type = $propertyMetadata->getType()) {
            return null;
        }

        if (
            Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
            && null !== ($nextResourceClass = $type->getClassName())
            && $this->resourceClassResolver->isResourceClass($nextResourceClass)
        ) {
            $nestedPath = $this->getNestedFieldPath($nextResourceClass, implode('.', $properties));

            return null === $nestedPath ? $currentProperty : "$currentProperty.$nestedPath";
        }

        if (
            null !== ($type = $type->getCollectionValueType())
            && Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
            && null !== ($className = $type->getClassName())
            && $this->resourceClassResolver->isResourceClass($className)
        ) {
            return $currentProperty;
        }

        return null;
    }
}
