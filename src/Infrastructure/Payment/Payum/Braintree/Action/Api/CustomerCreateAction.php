<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\CustomerCreateRequest;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\RequestNotSupportedException;
use ArrayAccess;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Braintree\Exception as BraintreeBaseException;

class CustomerCreateAction extends BaseApiAwareAction
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param CustomerCreateRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $response = null;

        try {
            $response = $this->api->customer()->create($model->toUnsafeArray());
        } catch (BraintreeBaseException $e) {
            throw new HttpException($e->getMessage());
        }

        if (false === $response->success) {
            $model->replace($response->jsonSerialize());
        }

        $model->replace((array)$response);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof CustomerCreateRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}