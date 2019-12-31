<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionVoidRequest;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Cancel;
use ArrayAccess;

class CancelAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Cancel $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute(new TransactionVoidRequest($model));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof Cancel && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}