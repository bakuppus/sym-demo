<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Utils;

use LogicException;

trait BraintreeErrorParser
{
    public function parseErrorFromResponse(array $responseArray): string
    {
        if (false === isset($responseArray['errors'])) {
            throw new LogicException('Braintree response does not contain errors');
        }

        $errors = $responseArray['errors']->deepAll();
        $errorString = '';

        foreach ($errors as $error) {
            $errorString .= $error->__get('message') . ' ';
        }

        return trim($errorString);
    }
}