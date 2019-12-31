<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Strategy;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;

class FailedStatusStrategy
{
    public function validate(ArrayObject $model): bool
    {
        if (true === isset($model['errors']) && null !== $model['errors']) {
            return true;
        }

        return false;
    }

    public function markStatus(GetStatusInterface $request): void
    {
        $request->markFailed();
    }
}