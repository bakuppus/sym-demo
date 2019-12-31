<?php

declare(strict_types=1);

namespace App\Application\Assigner\Order\Token;

use App\Domain\Order\Core\OrderInterface;
use Hashids\HashidsInterface;

final class HashedOrderTokenAssigner implements OrderTokenAssignerInterface
{
    /** @var HashidsInterface */
    private $hashids;

    public function __construct(HashidsInterface $hashids)
    {
        $this->hashids = $hashids;
    }

    public function assignToken(OrderInterface $order): void
    {
        $order->setToken($this->hashids->encode($order->getNumber()));
    }

    public function assignTokenIfNotSet(OrderInterface $order): void
    {
        if (null === $order->getToken()) {
            $this->assignToken($order);
        }
    }
}
