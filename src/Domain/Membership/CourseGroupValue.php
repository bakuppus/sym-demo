<?php

declare(strict_types=1);

namespace App\Domain\Membership;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @deprecated
 * @ORM\Entity
 * @ORM\Table(
 *      name="golf_course_group_values",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="FK_IUHTOTL2O1CES3MZ",
 *              columns={"membership_rule_id", "membership_golf_course_group_id"}
 *          )
 *      }
 * )
 */
class CourseGroupValue
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var MembershipRule
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Membership\MembershipRule", inversedBy="courseGroupValues")
     */
    private $membershipRule;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $value;

    /**
     * @var MembershipCourseGroup|null
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Domain\Membership\MembershipCourseGroup",
     *      inversedBy="courseGroupValues",
     *      cascade={"remove"}
     * )
     * @ORM\JoinColumn(name="membership_golf_course_group_id", onDelete="CASCADE")
     */
    private $membershipCourseGroup;

    public function getMembershipRule(): MembershipRule
    {
        return $this->membershipRule;
    }

    public function setMembershipRule(MembershipRule $membershipRule): self
    {
        $this->membershipRule = $membershipRule;

        return $this;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): self
    {
        $this->value = $value;

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
