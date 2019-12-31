<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\ClientTokenGenerateRequest;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\Exception\RequestNotSupportedException;
use ArrayAccess;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Braintree\Exception as BraintreeBaseException;
use LogicException;

class ClientTokenGenerateAction extends BaseApiAwareAction
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ClientTokenGenerateRequest $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $response = null;

        try {
            $response = $this->api->clientToken()->generate($model->toUnsafeArray());
        } catch (BraintreeBaseException | LogicException $e) {
            throw new HttpException($e->getMessage());
        }

        $model['token'] = $response;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof ClientTokenGenerateRequest && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}