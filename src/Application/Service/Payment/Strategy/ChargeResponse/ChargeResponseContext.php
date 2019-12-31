<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\ChargeResponse;

use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Domain\Payment\Core\PaymentInterface;

class ChargeResponseContext
{
    /** @var iterable|ChargeResponseStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function updatePaymentByResponse(PaymentInterface $payment, array $responseData, string $status): void
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->validate($status)) {
                $strategy->updatePaymentByResponse($payment, $responseData);

                return;
            }
        }

        throw new PaymentLogicException("Status {$status} is not supported by any status strategy");
    }
}