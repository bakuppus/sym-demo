<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Utils;

trait BraintreeMoneyConverter
{
    public function getAmountForBraintree(int $amount): string
    {
        $amountInMoney = $amount / 100;
        $convertedAmount = number_format($amountInMoney, 2, '.', '');

        return $convertedAmount;
    }
}