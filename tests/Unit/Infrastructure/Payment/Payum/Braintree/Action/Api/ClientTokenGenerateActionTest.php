<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Action\Api\ClientTokenGenerateAction;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\ClientTokenGenerateRequest;
use Braintree\ClientTokenGateway;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;

class ClientTokenGenerateActionTest extends TestCase
{
    public function testSuccessfulClientTokenGenerate()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);
        $apiMock = $this->createMock(BraintreeGateway::class);
        $clientTokenFactory = $this->createMock(ClientTokenGateway::class);

        $token = 'eyJ2';

        $clientTokenFactory
            ->method('generate')
            ->willReturn($token);

        $apiMock
            ->method('clientToken')
            ->willReturn($clientTokenFactory);

        $action = new ClientTokenGenerateAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $request = new ClientTokenGenerateRequest([]);

        $action->execute($request);

        $model = $request->getModel();

        $this->assertEquals($token, $model['token']);
    }
}