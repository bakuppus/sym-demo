<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy;

use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

trait UuidTrait
{
    /**
     * @var \Ramsey\Uuid\Uuid Has to be full namespace
     *
     * @Groups("Default")
     *
     * @ORM\Column(type="uuid", unique=true)
     *
     */
    private $uuid;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     *
     * @throws Exception
     */
    public function generateNewUuid(): self
    {
        $this->uuid = Uuid::uuid4();

        return $this;
    }
}
