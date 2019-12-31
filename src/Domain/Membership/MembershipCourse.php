<?php

declare(strict_types=1);

namespace App\Domain\Membership;

use App\Domain\Course\Course;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @deprecated
 * @ORM\Entity
 * @ORM\Table(
 *      name="membership_golf_courses",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="FK_EOFV53B6ZOT1GC2Y", columns={"membership_id", "golf_course_id"})
 *      }
 * )
 */
class MembershipCourse
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var Membership
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="membershipCourses")
     */
    private $membership;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course", inversedBy="membershipCourses")
     * @ORM\JoinColumn(name="golf_course_id")
     */
    private $course;

    /**
     * @var MembershipCourseGroup|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Membership\MembershipCourseGroup", inversedBy="membershipCourses")
     * @ORM\JoinColumn(name="membership_golf_course_group_id", onDelete="CASCADE")
     */
    private $membershipCourseGroup;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getMembership(): Membership
    {
        return $this->membership;
    }

    public function setMembership(Membership $membership): self
    {
        $this->membership = $membership;

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

    public function getMembershipCourseGroup(): ?MembershipCourseGroup
    {
        return $this->membershipCourseGroup;
    }

    public function setMembershipCourseGroup(?MembershipCourseGroup $membershipCourseGroup): self
    {
        $this->membershipCourseGroup = $membershipCourseGroup;

        return $this;
    }
}
