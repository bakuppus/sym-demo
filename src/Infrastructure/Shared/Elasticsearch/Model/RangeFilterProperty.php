<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Model;

class RangeFilterProperty
{
    /** @var string */
    protected $predicate;

    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    public function getPredicate(): ?string
    {
        return $this->predicate;
    }

    public function setPredicate(?string $predicate): void
    {
        $this->predicate = $predicate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
