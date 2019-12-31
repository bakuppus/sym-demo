<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Doctrine\Traits\Mapping;

use App\Domain\Shared\ValueObject\Origin;
use Doctrine\ORM\Mapping as ORM;

trait OriginTrait
{
    /**
     * @var Origin|null
     *
     * @ORM\Embedded(class="App\Domain\Shared\ValueObject\Origin")
     */
    protected $origin;

    public function getOrigin(): Origin
    {
        return $this->origin;
    }

    /**
     * Add origin to constructor
     */
    protected function createOrigin(): void
    {
        $this->origin = new Origin();
    }
}
