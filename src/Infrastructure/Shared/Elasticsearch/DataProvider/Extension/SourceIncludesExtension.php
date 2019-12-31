<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\DataProvider\Extension;

use ApiPlatform\Core\Bridge\Elasticsearch\Serializer\NameConverter\InnerFieldsNameConverter;
use Elastica\Query;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

final class SourceIncludesExtension implements QueryExtensionInterface
{
    /** @var PropertyInfoExtractorInterface */
    private $propertyInfoExtractor;

    /** @var InnerFieldsNameConverter */
    private $nameConverter;

    public function __construct(
        PropertyInfoExtractorInterface $propertyInfoExtractor,
        InnerFieldsNameConverter $nameConverter
    ) {
        $this->propertyInfoExtractor = $propertyInfoExtractor;
        $this->nameConverter = $nameConverter;
    }

    /**
     * {@inheritDoc}
     */
    public function applyToQuery(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        $sourceFields = $this->propertyInfoExtractor->getProperties($resourceClass,
            ['serializer_groups' => $context['groups']]);

        foreach ($sourceFields ?? [] as $key => $field) {
            $sourceFields[$key] = $this->nameConverter->normalize($field, $resourceClass, null, $context);
        }

        $query->setSource($sourceFields);

        return $query;
    }
}
