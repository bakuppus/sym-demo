<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Strategy;

use Braintree\Transaction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Request\GetStatusInterface;

class PendingStatusStrategy implements StatusStrategyInterface
{
    public function validate(ArrayObject $model): bool
    {
        try {
            $model->validateNotEmpty([
                'transaction'
            ]);
        } catch (LogicException $e) {
            return false;
        }

        $transaction = is_array($model['transaction']) ? $model['transaction'] : $model['transaction']->jsonSerialize();

        if (null !== $transaction && Transaction::AUTHORIZED === $transaction['status']) {
            return true;
        }

        return false;
    }

    public function markStatus(GetStatusInterface $request): void
    {
        $request->markPending();
    }
}