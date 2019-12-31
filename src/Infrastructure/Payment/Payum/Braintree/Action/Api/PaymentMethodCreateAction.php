<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\PaymentMethodCreateRequest;
use ArrayAccess;
use Braintree\Exception as BraintreeBaseException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;

class PaymentMethodCreateAction extends BaseApiAwareAction
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param PaymentMethodCreateRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $response = null;

        try {
            $response = $this->api->paymentMethod()->create($model->toUnsafeArray());
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
        if ($request instanceof PaymentMethodCreateRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}