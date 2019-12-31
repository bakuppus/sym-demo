<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Meta;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Meta;
use LogicException;

final class MetaContext
{
    /** @var iterable|MetaStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param PaymentInterface $payment
     *
     * @return array|Meta[]
     */
    public function getMeta(PaymentInterface $payment): array
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->supports($payment)) {
                return $strategy->getMeta($payment);
            }
        }

        throw new LogicException('No meta strategies supported for given payment');
    }
}