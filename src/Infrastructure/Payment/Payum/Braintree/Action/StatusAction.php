<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Strategy\StatusContext;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use ArrayAccess;

class StatusAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (true === $model->offsetExists('transaction') && null === $model->get('transaction')) {
            $request->markFailed();

            return;
        }

        $context = new StatusContext();
        $context->markStatus($model, $request);

        return;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        if ($request instanceof GetStatusInterface && $request->getModel() instanceof ArrayAccess) {
            return true;
        }

        return false;
    }
}