<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionSaleRequest;
use ArrayAccess;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\LogicException;
use Braintree\Exception as BraintreeBaseException;

class TransactionSaleAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param TransactionSaleRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false === ($model['paymentMethodNonce'] || $model['paymentMethodToken'])) {
            throw new LogicException('The either payment method nonce or payment method token has to be set.');
        }

        $model->validateNotEmpty([
            'amount',
        ]);

        try {
            $response = $this->api->transaction()->sale($model->toUnsafeArray());
        } catch (BraintreeBaseException $e) {
            throw new HttpException($e->getMessage());
        }

        if (false === $response->success) {
            $model->replace($response->jsonSerialize());

            return;
        }

        $model['transaction'] = $response->transaction->jsonSerialize();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof TransactionSaleRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}