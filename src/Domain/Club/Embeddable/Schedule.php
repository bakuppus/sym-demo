<?php

declare(strict_types=1);

namespace App\Domain\Club\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Schedule
 *
 * @package App\DAO\Doctrine\Embeddable
 *
 * @ORM\Embeddable
 */
class Schedule
{
    public const SUNDAY = 'sunday';
    public const MONDAY = 'monday';
    public const TUESDAY = 'tuesday';
    public const WEDNESDAY = 'wednesday';
    public const THURSDAY = 'thursday';
    public const FRIDAY = 'friday';
    public const SATURDAY = 'saturday';

    /**
     * @var bool $isEnabled
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    protected $isEnabled = false;

    /**
     * @var array|null $settings
     *
     * @ORM\Column(type="json", nullable=true)
     */
    protected $settings;

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getSettings(): ?array
    {
        return $this->settings;
    }

    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }
}
