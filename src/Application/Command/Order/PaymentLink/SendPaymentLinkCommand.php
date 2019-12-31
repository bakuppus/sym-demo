<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PaymentLink;

use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;

final class SendPaymentLinkCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @return Order|object
     */
    public function getResource(): object
    {
        return $this->objectToPopulate;
    }

    /**
     * @return Order|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
