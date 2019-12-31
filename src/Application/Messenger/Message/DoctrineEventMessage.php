<?php

declare(strict_types=1);

namespace App\Application\Messenger\Message;

class DoctrineEventMessage
{
    /** @var string */
    protected $laravelEntity;

    /** @var int */
    protected $identification;

    /** @var string */
    protected $eventName;

    /** @var array */
    protected $originalEntityData;

    public function __construct(
        string $laravelEntity,
        int $identification,
        string $eventName,
        ?array $originalEntityData
    ) {
        $this->laravelEntity = $laravelEntity;
        $this->identification = $identification;
        $this->eventName = $eventName;
        $this->originalEntityData = $originalEntityData;
    }

    public function getLaravelEntity(): ?string
    {
        return $this->laravelEntity;
    }

    public function getIdentification(): ?int
    {
        return $this->identification;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function getOriginalEntityData(): ?array
    {
        return $this->originalEntityData;
    }
}
