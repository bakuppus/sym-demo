<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Item;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Item;
use LogicException;

final class ItemContext
{
    /** @var iterable|ItemStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param PaymentInterface $payment
     *
     * @return array|Item[]
     */
    public function getItems(PaymentInterface $payment): array
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->supports($payment)) {
                return $strategy->getItems($payment);
            }
        }

        throw new LogicException('No item strategies supported for given payment');
    }
}