<?php

declare(strict_types=1);

namespace App\Domain\TeeTime;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class PeriodRule
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use PriorityTrait;

    public const DAYS_EVERY_DAY = 0;
    public const DAYS_WEEKENDS = 1;
    public const DAYS_WEEK_DAYS = 2;
    public const DAYS_MONDAY = 3;
    public const DAYS_TUESDAY = 4;
    public const DAYS_WEDNESDAY = 5;
    public const DAYS_THURSDAY = 6;
    public const DAYS_FRIDAY = 7;
    public const DAYS_SATURDAY = 8;
    public const DAYS_SUNDAY = 9;

    public const DAYS = [
        self::DAYS_EVERY_DAY,
        self::DAYS_WEEKENDS,
        self::DAYS_WEEK_DAYS,
        self::DAYS_MONDAY,
        self::DAYS_TUESDAY,
        self::DAYS_WEDNESDAY,
        self::DAYS_THURSDAY,
        self::DAYS_FRIDAY,
        self::DAYS_SATURDAY,
        self::DAYS_SUNDAY,
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @var int
     *
     * @ORM\Column(name="`interval`", type="integer")
     */
    private $interval;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $slots;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isGolfIdRequired;

    /**
     * @var Period|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\TeeTime\Period", inversedBy="rules", cascade={"persist"})
     */
    private $period;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getDays(): int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function setSlots(int $slots): self
    {
        $this->slots = $slots;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function setIsGolfIdRequired(bool $isGolfIdRequired): self
    {
        $this->isGolfIdRequired = $isGolfIdRequired;

        return $this;
    }

    public function isGolfIdRequired(): bool
    {
        return $this->isGolfIdRequired;
    }

    public function getPeriod(): ?Period
    {
        return $this->period;
    }

    public function setPeriod(?Period $period = null): self
    {
        $this->period = $period;

        return $this;
    }
}
