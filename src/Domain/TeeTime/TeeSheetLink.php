<?php

declare(strict_types=1);

namespace App\Domain\TeeTime;

use App\Domain\Course\Course;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class TeeSheetLink
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $hash;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="teeSheetLinks")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="available_date", type="datetime")
     */
    private $availableDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $locale;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function getAvailableDate(): DateTime
    {
        return $this->availableDate;
    }

    public function setAvailableDate(DateTime $availableDate): self
    {
        $this->availableDate = $availableDate;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
