<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Action\RefundAction;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionRefundRequest;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Refund;
use PHPUnit\Framework\TestCase;

class RefundActionTest extends TestCase
{
    public function testShouldSubExecuteTransactionRefund()
    {
        $model = [
            'transaction' => [
                'id' => 'transactionId',
            ],
            'amount' => 500,
        ];

        $gatewayMock = $gatewayMock = $this->createMock(GatewayInterface::class);
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(TransactionRefundRequest::class));

        $action = new RefundAction();
        $action->setGateway($gatewayMock);
        $action->execute(new Refund($model));
    }
}