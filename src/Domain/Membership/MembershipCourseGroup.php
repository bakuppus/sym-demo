<?php

declare(strict_types=1);

namespace App\Domain\Membership;

use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @deprecated
 * @ORM\Entity
 * @ORM\Table(name="memberships_golf_course_groups")
 */
class MembershipCourseGroup
{
    use AutoTrait;
    use TimestampableEntity;
    use UuidTrait;
    use SoftDeleteableEntity;

    /**
     * @var CourseGroupValue[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\CourseGroupValue", mappedBy="membershipCourseGroup")
     */
    private $courseGroupValues;

    /**
     * @var MembershipCourse[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Membership\MembershipCourse", mappedBy="membershipCourseGroup")
     */
    private $membershipCourses;

    /**
     * @var Membership
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="membershipCourseGroups")
     */
    private $membership;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return CourseGroupValue[]|Collection
     */
    public function getCourseGroupValues(): Collection
    {
        return $this->courseGroupValues;
    }

    /**
     * @return MembershipCourse[]|Collection
     */
    public function getMembershipCourses(): Collection
    {
        return $this->membershipCourses;
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

    public function setCourseGroupValues(Collection $courseGroupValues)
    {
        $this->courseGroupValues = $courseGroupValues;

        return $this;
    }

    public function setMembershipCourses(Collection $membershipCourses)
    {
        $this->membershipCourses = $membershipCourses;

        return $this;
    }
}
