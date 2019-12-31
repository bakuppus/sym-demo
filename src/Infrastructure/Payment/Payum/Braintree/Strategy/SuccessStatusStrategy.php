<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Strategy;

use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use Braintree\Transaction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Request\GetStatusInterface;

class SuccessStatusStrategy implements StatusStrategyInterface
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

        $isSuccessfulSale =
            true === in_array($transaction['status'], BraintreeGateway::TRANSACTION_SUCCESS_STATUSES) &&
            Transaction::SALE === $transaction['type'];

        return true === $isSuccessfulSale;
    }

    public function markStatus(GetStatusInterface $request): void
    {
        $request->markCaptured();
    }
}