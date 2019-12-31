<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionRefundRequest;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Refund;
use ArrayAccess;

class RefundAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Refund $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute(new TransactionRefundRequest($model));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof Refund && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}