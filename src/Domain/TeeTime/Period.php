<?php

declare(strict_types=1);

namespace App\Domain\TeeTime;

use App\Domain\Course\Course;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Period
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="periods", cascade={"persist"})
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var PeriodRule[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\TeeTime\PeriodRule", mappedBy="period", cascade={"persist"})
     */
    private $rules;

    /**
     * @var PeriodOverride[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\TeeTime\PeriodOverride", mappedBy="period", cascade={"persist"})
     */
    private $overrides;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->rules = new ArrayCollection();
        $this->overrides = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
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

    public function getRules()
    {
        return $this->rules;
    }

    public function addRule(PeriodRule $rule): self
    {
        if (false === $this->getRules()->contains($rule)) {
            $this->getRules()->add($rule);
            $rule->setPeriod($this);
        }

        return $this;
    }

    public function getOverrides()
    {
        return $this->overrides;
    }

    public function addOverride(PeriodOverride $override): self
    {
        if (false == $this->getRules()->contains($override)) {
            $this->getOverrides()->add($override);
            $override->setPeriod($this);
        }

        return $this;
    }
}
