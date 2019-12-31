<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Application\Command\Order\CreateOrder\Item\CreateOrderItemMembershipCardCommand;
use App\Domain\Order\OrderMembership;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderMembershipCommand extends CreateOrderCommand
{
    /**
     * @var int|Membership
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
     * @CommandBind(targetEntity="App\Domain\Promotion\Membership")
     */
    public $membership;

    /**
     * @var CreateOrderItemMembershipCardCommand[]
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *              "type"="array",
     *              "items"={
     *                  "type"="object",
     *                  "properties"={
     *                      "membership_card"={"type"="integer", "example"=1},
     *                      "quantity"={"type"="integer", "example"=1},
     *                      "total"={"type"="integer", "example"=100}
     *                  }
     *              }
     *         }
     *     }
     * )
     *
     * @Groups({"create_order_membership"})
     *
     * @Assert\Valid(groups={"create_order_membership"})
     *
     * @CommandBind(isTraverse=true)
     */
    public $items = [];

    /**
     * @return OrderMembership|object
     */
    public function getResource(): object
    {
        $order = new OrderMembership();
        $order->setClub($this->club);
        $order->setCourse($this->course);
        $order->setCustomer($this->customer);
        $order->setCurrencyCode($this->currencyCode);
        $order->setLocaleCode($this->localeCode);
        $order->setNotes($this->notes);
        $order->setMembership($this->membership);

        foreach ($this->items as $item) {
            $order->addItem($item->getResource());
        }

        return $order;
    }
}
