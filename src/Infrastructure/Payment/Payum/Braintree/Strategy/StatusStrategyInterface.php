<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Strategy;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;

interface StatusStrategyInterface
{
    public function markStatus(GetStatusInterface $request);
    public function validate(ArrayObject $model): bool;
}