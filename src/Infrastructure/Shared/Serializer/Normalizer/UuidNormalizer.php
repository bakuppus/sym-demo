<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Serializer\Normalizer;

use ApiPlatform\Core\Exception\InvalidIdentifierException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class UuidNormalizer implements DenormalizerInterface
{
    /**
     * {@inheritDoc}
     * @throws InvalidIdentifierException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        try {
            return Uuid::fromString($data);
        } catch (InvalidUuidStringException $e) {
            throw new InvalidIdentifierException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return true === is_a($type, UuidInterface::class, true) && true === Uuid::isValid($data);
    }
}