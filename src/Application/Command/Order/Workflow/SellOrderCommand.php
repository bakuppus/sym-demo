<?php

declare(strict_types=1);

namespace App\Application\Command\Order\Workflow;

use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;

final class SellOrderCommand implements CommandPopulatableInterface, CommandAwareInterface
{
    use CommandPopulatableTrait;

    /** @var string */
    public $transition = Order::PAYMENT_TRANSITION_PAY;

    /**
     * @return Order|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }

    /**
     * @return Order|object
     */
    public function getResource(): object
    {
        return $this->objectToPopulate;
    }
}
