<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\UpdatePaymentMethod;

use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Domain\Payment\Core\PaymentInterface;

class UpdatePaymentMethodContext
{
    /** @var iterable|UpdatePaymentMethodStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function updatePaymentMethod(PaymentInterface $payment, string $methodName): PaymentInterface
    {
        foreach ($this->strategies as $strategy) {
            if (true === $strategy->supports($methodName)) {
                $payment = $strategy->updatePaymentMethod($payment);

                return $payment;
            }
        }

        throw new PaymentLogicException("Method {$methodName} is not supported");
    }
}