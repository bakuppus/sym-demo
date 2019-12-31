<?php

declare(strict_types=1);

namespace App\Application\Command\Order\SetOrderPaymentRefund;

use App\Domain\Order\Core\OrderInterface;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;

final class OrderPaymentRefundCommand implements CommandPopulatableInterface, CommandAwareInterface
{
    use CommandPopulatableTrait;

    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }

    /**
     * @return object|OrderInterface
     */
    public function getResource(): object
    {
        return $this->objectToPopulate;
    }
}