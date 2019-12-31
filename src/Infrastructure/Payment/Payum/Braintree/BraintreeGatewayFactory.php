<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Payum\Braintree;

use Braintree;
use Payum\Core\Exception\LogicException;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\ClientTokenGenerateAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\CustomerCreateAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\PaymentMethodCreateAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionFindAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionRefundAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionSaleAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionVoidAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\CancelAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\CaptureAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\RefundAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class BraintreeGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config)
    {
        if (false === class_exists(Braintree::class)) {
            throw new LogicException('You must install "braintree/braintree_php" library.');
        }

        $config->defaults([
            'payum.factory_name' => BraintreeGateway::GATEWAY_FACTORY_NAME,
            'payum.factory_title' => 'Braintree',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.customer_create' => new CustomerCreateAction(),
            'payum.action.transaction_sale' => new TransactionSaleAction(),
            'payum.action.payment_method_create' => new PaymentMethodCreateAction(),
            'payum.action.transaction_refund' => new TransactionRefundAction(),
            'payum.action.transaction_void' => new TransactionVoidAction(),
            'payum.action.client_token_generate' => new ClientTokenGenerateAction(),
            'payum.action.transaction_find' => new TransactionFindAction(),
        ]);

        if (null === $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => '',
                'merchant_id' => '',
                'public_key' => '',
                'private_key' => '',
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'environment',
                'merchant_id',
                'public_key',
                'private_key',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $braintreeGateway = new BraintreeGateway([
                    'environment' => $config['environment'],
                    'merchantId' => $config['merchant_id'],
                    'publicKey' => $config['public_key'],
                    'privateKey' => $config['private_key'],
                ]);

                return $braintreeGateway;
            };
        }
    }
}