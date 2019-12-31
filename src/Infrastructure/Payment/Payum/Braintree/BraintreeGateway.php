<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree;

use Braintree\Gateway;
use Braintree\Transaction;

final class BraintreeGateway extends Gateway
{
    public const TRANSACTION_SUCCESS_STATUSES = [
        Transaction::SUBMITTED_FOR_SETTLEMENT,
        Transaction::SETTLING,
        Transaction::SETTLED,
    ];

    public const ENVIRONMENT_SANDBOX = 'sandbox';
    public const ENVIRONMENT_PRODUCTION = 'production';
    public const GATEWAY_FACTORY_NAME = 'braintree';
    public const PRODUCTION_GATEWAY_NAME = 'braintree';
    public const SANDBOX_GATEWAY_NAME = 'braintree_sandbox';
}