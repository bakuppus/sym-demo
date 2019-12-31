<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Course\Course;
use App\Domain\Promotion\Membership;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"Default"}},
 *     denormalizationContext={"groups"={"Default"}},
 *
 *     collectionOperations={
 *           "get"
 *     },
 *     itemOperations={
 *          "get"
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"course": "exact", "player": "exact", "membership": "exact"})
 */
class TeeTimeBookingLimit
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course")
     * @ORM\JoinColumn(name="golf_course_id", nullable=false)
     */
    private $course;

    /**
     * @var Membership
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership")
     */
    private $membership;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $teeTimeStart;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $teeTimeEnd;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

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

    public function getMembership(): ?Membership
    {
        return $this->membership;
    }

    public function setMembership(?Membership $membership): self
    {
        $this->membership = $membership;

        return $this;
    }

    public function getTeeTimeStart(): DateTime
    {
        return $this->teeTimeStart;
    }

    public function setTeeTimeStart(DateTime $teeTimeStart): self
    {
        $this->teeTimeStart = $teeTimeStart;

        return $this;
    }

    public function getTeeTimeEnd(): DateTime
    {
        return $this->teeTimeEnd;
    }

    public function setTeeTimeEnd(DateTime $teeTimeEnd): self
    {
        $this->teeTimeEnd = $teeTimeEnd;

        return $this;
    }
}
