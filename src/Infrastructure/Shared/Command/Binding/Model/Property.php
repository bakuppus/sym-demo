<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command\Binding\Model;

final class Property
{
    /** @var string */
    public $name;

    /** @var mixed */
    public $value;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function isObject(): bool
    {
        return is_object($this->value);
    }

    public function isArray(): bool
    {
        return is_array($this->value);
    }

    public function isInt(): bool
    {
        return is_int($this->value);
    }
}
