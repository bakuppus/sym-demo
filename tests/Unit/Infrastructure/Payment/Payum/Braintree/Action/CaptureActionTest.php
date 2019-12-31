<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Action\CaptureAction;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionSaleRequest;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\TestCase;

class CaptureActionTest extends TestCase
{
    public function testShouldSubExecuteTransactionSale()
    {
        $model = [
            'paymentMethodNonce' => '123456',
            'amount' => 500,
        ];

        $gatewayMock = $gatewayMock = $this->createMock(GatewayInterface::class);
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(TransactionSaleRequest::class));

        $action = new CaptureAction();
        $action->setGateway($gatewayMock);
        $action->execute(new Capture($model));
    }
}