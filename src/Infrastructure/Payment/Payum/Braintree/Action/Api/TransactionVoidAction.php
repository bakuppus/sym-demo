<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionVoidRequest;
use ArrayAccess;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Braintree\Exception as BraintreeBaseException;

class TransactionVoidAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param TransactionVoidRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $transactionId = isset($model['transaction']['id']) &&
            null !== $model['transaction']['id'] ?
            $model['transaction']['id'] : null;

        if (null === $transactionId) {
            throw new LogicException('The transaction id is required');
        }

        try {
            $response = $this->api->transaction()->void($transactionId);
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
        if ($request instanceof TransactionVoidRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}