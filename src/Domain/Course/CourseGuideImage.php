<?php

declare(strict_types=1);

namespace App\Domain\Course;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="golf_course_guide_images")
 */
class CourseGuideImage
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var CourseGuide
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Domain\Course\CourseGuide",
     *      inversedBy="images",
     *      fetch="EAGER",
     *      cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="golf_course_guide_id")
     */
    private $courseGuide;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hole;

    public function getCourseGuide(): CourseGuide
    {
        return $this->courseGuide;
    }

    public function setCourseGuide(CourseGuide $courseGuide): self
    {
        $this->courseGuide = $courseGuide;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHole(): ?int
    {
        return $this->hole;
    }

    public function setHole(?int $hole): self
    {
        $this->hole = $hole;

        return $this;
    }
}
