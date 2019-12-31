<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Strategy\RefundRequestOptions;

class RefundRequestOptionsContext
{
    /** @var iterable|RefundRequestOptionsStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getPreparedRequestOptions(string $gatewayName, int $paymentAmount, array $options): array
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->validate($gatewayName)) {
                $options = $strategy->getOptions($paymentAmount, $options);

                break;
            }
        }

        return $options;
    }
}