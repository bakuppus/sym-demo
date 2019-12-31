<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionRefundAction;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionRefundRequest;
use Braintree\Result\Successful;
use Braintree\Transaction;
use Braintree\TransactionGateway;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;

class TransactionRefundActionTest extends TestCase
{
    public function testSuccessfulRefund()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);
        $apiMock = $this->createMock(BraintreeGateway::class);
        $braintreeSuccessMock = $this->createMock(Successful::class);
        $transactionMock = $this->createMock(Transaction::class);
        $transactionGatewayMock = $this->createMock(TransactionGateway::class);

        $transactionResponseType = Transaction::CREDIT;
        $transactionResponseAmount = '100.00';

        $transactionMock->amount = $transactionResponseType;
        $transactionMock->type = $transactionResponseAmount;

        $transactionResponseProps = [
            'type' => $transactionResponseType,
            'amount' => $transactionResponseAmount,
        ];

        $transactionMock
            ->method('jsonSerialize')
            ->willReturn($transactionResponseProps);

        $braintreeSuccessMock->transaction = $transactionMock;

        $transactionGatewayMock
            ->method('refund')
            ->willReturn($braintreeSuccessMock);

        $apiMock
            ->method('transaction')
            ->willReturn($transactionGatewayMock);

        $model = new ArrayObject([
            'amount' => 100,
            'transaction' => [
                'id' => 'ekwaem15'
            ],
        ]);

        $action = new TransactionRefundAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $transactionRefundRequest = new TransactionRefundRequest($model);
        $action->execute($transactionRefundRequest);

        $expectedModel = clone $model;
        $expectedModel['transaction'] = $transactionResponseProps;

        $this->assertEquals($expectedModel, $transactionRefundRequest->getModel());
    }

    public function testRefundWithoutAmount()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);

        $model = new ArrayObject([
            'transactionId' => 'ekwaem15',
        ]);

        $action = new TransactionRefundAction();
        $action->setGateway($gatewayMock);

        $transactionSaleRequest = new TransactionRefundRequest($model);

        $this->expectException(LogicException::class);

        $action->execute($transactionSaleRequest);
    }

    public function testRefundWithoutTransactionId()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);

        $model = new ArrayObject([
            'amount' => 5000,
        ]);

        $action = new TransactionRefundAction();
        $action->setGateway($gatewayMock);

        $transactionSaleRequest = new TransactionRefundRequest($model);

        $this->expectException(LogicException::class);

        $action->execute($transactionSaleRequest);
    }
}