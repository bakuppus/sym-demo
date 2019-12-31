<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="golf_course_images")
 */
class CourseImage
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="images")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isNew = false;

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }
}
