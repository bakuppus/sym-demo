<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Application\Command\Order\CreateOrder\Item\CreateOrderItemCommand;
use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Domain\Order\Order;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderCommand implements CommandAwareInterface
{
    /**
     * @var int|Club
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
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="int", groups={"create_order"})
     *
     * @CommandBind(targetEntity="App\Domain\Club\Club")
     */
    public $club;

    /**
     * @var int|Course
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
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="int", groups={"create_order"})
     *
     * @CommandBind(targetEntity="App\Domain\Course\Course")
     */
    public $course;

    /**
     * @var int|Player
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
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="int", groups={"create_order"})
     *
     * @CommandBind(targetEntity="App\Domain\Player\Player")
     */
    public $customer;

    /**
     * @var CreateOrderItemCommand[]
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *              "type"="array",
     *              "items"={
     *                  "type"="object",
     *                  "properties"={
     *                      "quantity"={"type"="integer", "example"=1},
     *                      "total"={"type"="integer", "example"=100}
     *                  }
     *              }
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\Valid(groups={"create_order"})
     *
     * @CommandBind(isTraverse=true)
     */
    public $items = [];

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="sek"
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="string", groups={"create_order"})
     */
    public $currencyCode;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="se"
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(groups={"create_order"})
     * @Assert\Type(type="string", groups={"create_order"})
     */
    public $localeCode;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Voluptatem necessitatibus aspernatur iure voluptatem qui quisquam maiores officia."
     *         }
     *     }
     * )
     *
     * @Groups({"create_order"})
     *
     * @Assert\NotBlank(allowNull=true, groups={"create_order"})
     * @Assert\Type(type="string", groups={"create_order"})
     */
    public $notes;

    /**
     * @return Order|object
     */
    public function getResource(): object
    {
        $order = new Order();
        $order->setClub($this->club);
        $order->setCourse($this->course);
        $order->setCustomer($this->customer);
        $order->setCurrencyCode($this->currencyCode);
        $order->setLocaleCode($this->localeCode);
        $order->setNotes($this->notes);

        foreach ($this->items as $item) {
            $order->addItem($item->getResource());
        }

        return $order;
    }
}
