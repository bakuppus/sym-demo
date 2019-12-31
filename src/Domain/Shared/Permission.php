<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;

/**
 * @ORM\Entity()
 */
class Permission
{
    use AutoTrait;

    const TEE_TIMES_VIEW = 'tee_times_view';
    const TEE_TIME_BOOKING_CREATE = 'tee_time_booking_create';
    const TEE_TIME_BOOKING_READ = 'tee_time_booking_read';
    const TEE_TIME_BOOKING_UPDATE = 'tee_time_booking_update';
    const TEE_TIME_BOOKING_DELETE = 'tee_time_booking_delete';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }
}