<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Serializer\Normalizer\JsonLd;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\Hydra\Serializer\CollectionNormalizer as JsonLdCollectionNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CollectionNormalizer implements NormalizerInterface, SerializerAwareInterface, NormalizerAwareInterface
{
    /** @var JsonLdCollectionNormalizer */
    private $decorated;

    public function __construct(JsonLdCollectionNormalizer $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);

        $paginated = null;
        if ($paginated = $object instanceof PaginatorInterface) {
            $data['hydra:itemsPerPage'] = $paginated ? $object->getItemsPerPage() : \count($object);
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->decorated->setNormalizer($normalizer);
    }
}
