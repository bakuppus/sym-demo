<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder\Item;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Order\Item\OrderItem;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderItemCommand implements CommandAwareInterface
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="int", groups={"create_order"})
     */
    public $quantity = 1;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1000
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="int", groups={"create_order"})
     */
    public $total;

    /**
     * @return OrderItem|object
     */
    public function getResource(): object
    {
        $item = new OrderItem();
        $item->setQuantity($this->quantity);
        $item->setTotal($this->total);

        return $item;
    }
}
