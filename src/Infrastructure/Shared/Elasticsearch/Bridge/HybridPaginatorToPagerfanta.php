<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Bridge;

use App\Infrastructure\Elasticsearch\Event\Shared\ElasticHydrationEvent;
use FOS\ElasticaBundle\HybridResult;
use Pagerfanta\Adapter\AdapterInterface;
use FOS\ElasticaBundle\Paginator\HybridPaginatorAdapter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HybridPaginatorToPagerfanta implements AdapterInterface
{
    /** @var HybridPaginatorAdapter */
    protected $hybridPaginatorAdapter;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(
        HybridPaginatorAdapter $hybridPaginatorAdapter,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->hybridPaginatorAdapter = $hybridPaginatorAdapter;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getNbResults()
    {
        return $this->hybridPaginatorAdapter->getTotalHits();
    }

    public function getSlice($offset, $length)
    {
        $slice = $this->hybridPaginatorAdapter->getResults($offset, $length);
        $transformed = [];
        foreach ($slice->toArray() as $result) {
            /** @var HybridResult $result */
            $this->eventDispatcher->dispatch(new ElasticHydrationEvent($result));
            $transformed[] = $result->getTransformed();
        }

        return $transformed;
    }
}
