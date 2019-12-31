<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder\Item;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Order\Item\OrderItemMembershipCard;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrderItemMembershipCardCommand extends CreateOrderItemCommand
{
    /**
     * @var int|MembershipCard
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"create_order_membership"})
     *
     * @Assert\NotBlank(groups={"create_order_membership"})
     * @Assert\Type(type="int", groups={"create_order_membership"})
     *
     * @CommandBind(targetEntity="App\Domain\Promotion\MembershipCard")
     */
    public $membershipCard;

    /**
     * @return OrderItemMembershipCard|object
     */
    public function getResource(): object
    {
        $item = new OrderItemMembershipCard();
        $item->setMembershipCard($this->membershipCard);
        $item->setQuantity($this->quantity);
        $item->setTotal($this->total);

        return $item;
    }
}
