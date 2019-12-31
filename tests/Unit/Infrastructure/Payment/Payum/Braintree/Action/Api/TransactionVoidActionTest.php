<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionRefundAction;
use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionVoidAction;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionRefundRequest;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionVoidRequest;
use Braintree\Result\Successful;
use Braintree\Transaction;
use Braintree\TransactionGateway;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;

class TransactionVoidActionTest extends TestCase
{
    public function testSuccessfulVoid()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);
        $apiMock = $this->createMock(BraintreeGateway::class);
        $braintreeSuccessMock = $this->createMock(Successful::class);
        $transactionMock = $this->createMock(Transaction::class);
        $transactionGatewayMock = $this->createMock(TransactionGateway::class);

        $transactionResponseType = Transaction::SALE;
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
            ->method('void')
            ->willReturn($braintreeSuccessMock);

        $apiMock
            ->method('transaction')
            ->willReturn($transactionGatewayMock);

        $model = new ArrayObject([
            'transaction' => [
                'id' => 'ekwaem15'
            ],
        ]);

        $action = new TransactionVoidAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $transactionSaleRequest = new TransactionVoidRequest($model);
        $action->execute($transactionSaleRequest);

        $expectedModel = clone $model;
        $expectedModel['transaction'] = $transactionResponseProps;

        $this->assertEquals($expectedModel, $transactionSaleRequest->getModel());
    }

    public function testVoidWithoutTransactionId()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);

        $action = new TransactionRefundAction();
        $action->setGateway($gatewayMock);

        $transactionSaleRequest = new TransactionRefundRequest([]);

        $this->expectException(LogicException::class);

        $action->execute($transactionSaleRequest);
    }
}