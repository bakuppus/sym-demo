<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Info;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Info;
use LogicException;

final class InfoContext
{
    /** @var iterable|InfoStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getInfo(PaymentInterface $payment): Info
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->supports($payment)) {
                return $strategy->getInfo($payment);
            }
        }

        throw new LogicException('No receipt info strategies supported for given payment');
    }
}