<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionFindRequest;
use ArrayAccess;
use Braintree\Exception as BraintreeBaseException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\RequestNotSupportedException;

class TransactionFindAction extends BaseApiAwareAction
{
    /**
     * @param TransactionFindRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty([
            'transactionId',
        ]);

        try {
            $response = $this->api->transaction()->find($model['transactionId']);
        } catch (BraintreeBaseException $e) {
            throw new HttpException($e->getMessage());
        }

        $model->replace($response->jsonSerialize());
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof TransactionFindRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}