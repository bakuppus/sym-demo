<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Bridge;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use IteratorAggregate;
use Pagerfanta\Pagerfanta;
use Traversable;

final class Paginator implements IteratorAggregate, PaginatorInterface
{
    private $pagerfanta;

    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastPage(): float
    {
        return $this->pagerfanta->getNbPages();
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalItems(): float
    {
        return $this->pagerfanta->getNbResults();
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentPage(): float
    {
        return $this->pagerfanta->getCurrentPage();
    }

    /**
     * {@inheritDoc}
     */
    public function getItemsPerPage(): float
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Traversable
    {
        foreach ($this->pagerfanta->getCurrentPageResults() ?? [] as $document) {
            yield $document;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return $this->pagerfanta->count();
    }
}
