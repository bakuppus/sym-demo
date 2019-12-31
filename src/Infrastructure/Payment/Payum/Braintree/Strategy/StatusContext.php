<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Strategy;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;

class StatusContext
{
    /** @var array|StatusStrategyInterface[] */
    private $strategyClasses = [
        FailedStatusStrategy::class,
        SuccessStatusStrategy::class,
        PendingStatusStrategy::class,
        RefundStatusStrategy::class,
    ];

    public function markStatus(ArrayObject $model, GetStatusInterface $request): void
    {
        foreach ($this->strategyClasses as $strategyClass) {
            /** @var StatusStrategyInterface $strategy */
            $strategy = new $strategyClass();
            if (true === $strategy->validate($model)) {
                $strategy->markStatus($request);

                return;
            }
        }

        $request->markUnknown();
    }
}