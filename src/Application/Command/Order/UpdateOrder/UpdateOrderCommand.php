<?php

declare(strict_types=1);

namespace App\Application\Command\Order\UpdateOrder;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateOrderCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="sek"
     *         }
     *     }
     * )
     *
     * @Groups({"update_order"})
     *
     * @Assert\NotBlank(groups={"update_order"})
     * @Assert\Type(type="string", groups={"update_order"})
     */
    public $currencyCode;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="se"
     *         }
     *     }
     * )
     *
     * @Groups({"update_order"})
     *
     * @Assert\NotBlank(groups={"update_order"})
     * @Assert\Type(type="string", groups={"update_order"})
     */
    public $localCode;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Voluptatem necessitatibus aspernatur iure voluptatem qui quisquam maiores officia."
     *         }
     *     }
     * )
     *
     * @Groups({"update_order"})
     *
     * @Assert\NotBlank(groups={"update_order"})
     * @Assert\Type(type="string", groups={"update_order"})
     */
    public $notes;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="new"
     *         }
     *     }
     * )
     *
     * @Groups({"update_order"})
     *
     * @Assert\NotBlank(groups={"update_order"})
     * @Assert\Type(type="string", groups={"update_order"})
     */
    public $paymentState;

    /**
     * @return Order|object
     */
    public function getResource(): object
    {
        $order = $this->getObjectToPopulate();
        $order->setCurrencyCode($this->currencyCode);
        $order->setLocaleCode($this->localCode);
        $order->setNotes($this->notes);
        $order->setPaymentState($this->paymentState);

        return $order;
    }

    /**
     * @return Order|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
