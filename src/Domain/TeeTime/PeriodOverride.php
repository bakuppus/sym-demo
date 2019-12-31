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
class PeriodOverride
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $slots;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isGolfIdRequired;

    /**
     * @var Period
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\TeeTime\Period", inversedBy="overrides", cascade={"persist"})
     */
    private $period;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): self
    {
        $this->endDate = $endDate;

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

    public function setIsGolfIdRequired(bool $isGolfIdRequired): self
    {
        $this->isGolfIdRequired = $isGolfIdRequired;

        return $this;
    }

    public function isGolfIdRequired(): bool
    {
        return $this->isGolfIdRequired;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function setPeriod(Period $period): self
    {
        $this->period = $period;

        return $this;
    }
}
