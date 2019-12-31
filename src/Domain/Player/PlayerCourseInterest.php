<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Domain\Course\Course;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="player_golf_course_interests", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQ_2H60UB6CVSN4AHOU", columns={"player_id", "golf_course_id"})
 * })
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class PlayerCourseInterest /*implements EntityAwareInterface*/
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="golfCourseInterests")
     */
    private $player;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", fetch="EAGER")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

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
}
