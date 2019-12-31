<?php

declare(strict_types=1);

namespace App\Domain\Order\Item;

use App\Domain\Promotion\MembershipCard;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class OrderItemMembershipCard extends OrderItem
{
    /**
     * @var MembershipCard
     *
     * @Groups({"get_order_membership"})
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\MembershipCard")
     */
    private $membershipCard;

    public function getMembershipCard(): MembershipCard
    {
        return $this->membershipCard;
    }

    public function setMembershipCard(MembershipCard $member): void
    {
        $this->membershipCard = $member;
    }
}
