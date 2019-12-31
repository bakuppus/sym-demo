<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Accounting\Fee;
use App\Domain\Accounting\FeeInterface;
use App\Domain\Accounting\SubjectFeeInterface;
use App\Domain\Promotion\Core\MembershipInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity
 */
class MembershipFee extends Fee
{
    /**
     * @var SubjectFeeInterface|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership", inversedBy="fees")
     *
     * @Groups({"create_fee", "update_fee"})
     * @Assert\Valid(groups={"edit_membership"})
     */
    private $membership;

    /**
     * @return MembershipInterface|SubjectFeeInterface|null
     */
    public function getMembership(): ?SubjectFeeInterface
    {
        return $this->membership;
    }

    public function setMembership(?SubjectFeeInterface $membership): FeeInterface
    {
        $this->membership = $membership;

        return $this;
    }

    public function getSubject(): ?SubjectFeeInterface
    {
        return $this->membership;
    }

    public function setSubject(?SubjectFeeInterface $subject): FeeInterface
    {
        $this->membership = $subject;

        return $this;
    }
}
