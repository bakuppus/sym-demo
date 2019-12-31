<?php

declare(strict_types=1);

namespace App\Domain\Membership;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @deprecated
 * @ORM\Entity
 */
class MembershipRule
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $value;

    /**
     * @var CourseGroupValue[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\CourseGroupValue", mappedBy="membershipRule", cascade={"persist"})
     */
    private $courseGroupValues;

    public function __construct()
    {
        $this->courseGroupValues = new ArrayCollection();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getValue(): array
    {
        return (array)$this->value;
    }

    public function setValue(?array $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCourseGroupValues(): Collection
    {
        return $this->courseGroupValues;
    }

    public function addGolfCourseGroupValues(CourseGroupValue $golfCourseGroupValues): self
    {
        if (false === $this->getCourseGroupValues()->contains($golfCourseGroupValues)) {
            $this->getCourseGroupValues()->add($golfCourseGroupValues);
            $golfCourseGroupValues->setMembershipRule($this);
        }

        return $this;
    }
}
