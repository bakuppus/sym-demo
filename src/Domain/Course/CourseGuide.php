<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="golf_course_guides")
 */
class CourseGuide
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Course
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Course\Course", inversedBy="guide", fetch="EAGER")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default": 18})
     */
    private $numberOfHoles = 18;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isActive = false;

    /**
     * @var CourseGuideImage[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Domain\Course\CourseGuideImage",
     *      mappedBy="courseGuide",
     *      cascade={"remove"},
     *      fetch="LAZY",
     *      orphanRemoval=true
     * )
     */
    private $images;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->images = new ArrayCollection();
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

    public function getNumberOfHoles(): int
    {
        return $this->numberOfHoles;
    }

    public function setNumberOfHoles(int $numberOfHoles): self
    {
        $this->numberOfHoles = $numberOfHoles;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return CourseGuideImage[]|Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImages(Collection $images): self
    {
        $this->images = $images;

        return $this;
    }

//    /**
//     * @param CourseImage $image
//     *
//     * @return CourseGuide
//     */
//    public function addImage(CourseImage $image): CourseGuide
//    {
//        if (false === $this->images->contains($image)) {
//            $this->images->add($image);
//            $image->setGolfCourseGuide($this);
//        }
//
//        return $this;
//    }
}
