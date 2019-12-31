<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use DateTime;

/**
 * Class GolfClub
 *
 * @package App\DAO\Entities
 *
 * @ORM\Entity
 */
class BookingEmail
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /** @var string  */
    public const STATUS_SENT = 'sent';

    /** @var string  */
    public const STATUS_DRAFT = 'draft';

    /**
     * @var Club
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Club\Club",
     *     fetch="EAGER"
     * )
     */
    protected $golfClub;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $golfCourses;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $fromTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $toTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $sentAt;

    public function getGolfClub(): Club
    {
        return $this->golfClub;
    }

    public function setGolfClub(Club $golfClub): BookingEmail
    {
        $this->golfClub = $golfClub;

        return $this;
    }

    public function getGolfCourses(): array
    {
        return $this->golfCourses;
    }

    public function setGolfCourses(array $golfCourses): BookingEmail
    {
        $this->golfCourses = $golfCourses;

        return $this;
    }

    public function getFromTime(): DateTime
    {
        return $this->fromTime;
    }

    public function setFromTime(DateTime $fromTime): BookingEmail
    {
        $this->fromTime = $fromTime;

        return $this;
    }

    public function getToTime(): DateTime
    {
        return $this->toTime;
    }

    public function setToTime(DateTime $toTime): BookingEmail
    {
        $this->toTime = $toTime;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): BookingEmail
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): BookingEmail
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): BookingEmail
    {
        $this->status = $status;

        return $this;
    }

    public function getSentAt(): ?DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?DateTime $sentAt): BookingEmail
    {
        $this->sentAt = $sentAt;

        return $this;
    }
}
