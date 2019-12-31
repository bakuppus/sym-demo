<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Exceptions;

use ApiPlatform\Core\Exception\InvalidArgumentException;

class ElasticInvalidArgumentException extends InvalidArgumentException
{
    public static function notValidPredicate(string $propertyName): self
    {
        $message = sprintf("Not valid predicate for %s property", $propertyName);

        return new self($message);
    }

    public static function predicateRequired(string $propertyName): self
    {
        $message = sprintf("Predicate required for %s property", $propertyName);

        return new self($message);
    }

    public static function predicateLogicException(string $propertyName, string $predicate): self
    {
        $message = sprintf("Property %s predicate %s logic exception", $propertyName, $predicate);

        return new self($message);
    }
}
