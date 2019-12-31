<?php

declare(strict_types=1);

namespace App\Domain\Price;

use App\Domain\Course\Course;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 */
class PricePeriod
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="pricePeriods", cascade={"persist"})
     * @ORM\JoinColumn(name="golf_course_id", referencedColumnName="id", nullable=false)
     */
    private $course;

    /**
     * @var PriceModule[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Price\PriceModule", mappedBy="pricePeriod", cascade={"persist",
     *     "remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $priceModules;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $name;

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
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $workdays;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $weekends;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $overrides;

    /**
     * @var bool
     *
     * @ORM\Column(name="`default`", type="boolean", options={"default": 0})
     */
    private $default;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $active;

    public function __construct()
    {
        $this->priceModules = new ArrayCollection();
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

    public function getName(): string
    {
        return (string)$this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getWorkdays(): array
    {
        return $this->workdays;
    }

    public function setWorkdays(array $workdays): self
    {
        $this->workdays = $workdays;

        return $this;
    }

    public function getWeekends(): array
    {
        return $this->weekends;
    }

    public function setWeekends(array $weekends): self
    {
        $this->weekends = $weekends;

        return $this;
    }

    public function getOverrides(): array
    {
        return $this->overrides;
    }

    public function setOverrides(array $overrides): self
    {
        $this->overrides = $overrides;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    // TODO: Refactor `config` method
    public function getHolidays(): array
    {
        /*return array_map(function ($holiday) {
            return new Datetime($holiday);
        }, config('price.price_period.holidays'));*/

        return [];
    }

    public function getPriceModules(): Collection
    {
        return $this->priceModules;
    }

    public function addPriceModule(PriceModule $priceModule): self
    {
        if (false === $this->getPriceModules()->contains($priceModule)) {
            $this->getPriceModules()->add($priceModule);
            $priceModule->setPricePeriod($this);
        }

        return $this;
    }

    public function addPriceModules(Collection $priceModules): self
    {
        $priceModules->map(function (PriceModule $module) {
            $this->addPriceModule($module);
        });

        return $this;
    }
}
