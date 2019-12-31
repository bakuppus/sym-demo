<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\Event\Shared;

use FOS\ElasticaBundle\HybridResult;
use Symfony\Contracts\EventDispatcher\Event;

class ElasticHydrationEvent extends Event
{
    /** @var HybridResult */
    protected $hybridResult;

    public function __construct(HybridResult $hybridResult)
    {
        $this->hybridResult = $hybridResult;
    }

    public function getHybridResult(): ?HybridResult
    {
        return $this->hybridResult;
    }
}
