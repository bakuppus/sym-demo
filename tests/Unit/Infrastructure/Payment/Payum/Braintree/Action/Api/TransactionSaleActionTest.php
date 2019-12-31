<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action\Api;

use App\Infrastructure\Payment\Payum\Braintree\Action\Api\TransactionSaleAction;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\TransactionSaleRequest;
use App\Infrastructure\Shared\Utils\MoneyConverter;
use Braintree\Result\Successful;
use Braintree\Transaction;
use Braintree\TransactionGateway;
use Payum\Core\Exception\LogicException;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;
use Payum\Core\Bridge\Spl\ArrayObject;

class TransactionSaleActionTest extends TestCase
{
    use MoneyConverter;

    public function testSuccessfulCharge()
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

        $transactionGatewayMock->method('sale')->willReturn($braintreeSuccessMock);

        $apiMock
            ->method('transaction')
            ->willReturn($transactionGatewayMock);

        $model = new ArrayObject([
            'amount' => 100,
            'paymentMethodNonce' => '123456',
        ]);

        $action = new TransactionSaleAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $transactionSaleRequest = new TransactionSaleRequest($model);
        $action->execute($transactionSaleRequest);

        $expectedModel = clone $model;
        $expectedModel['transaction'] = $transactionResponseProps;

        $this->assertEquals($expectedModel, $transactionSaleRequest->getModel());
    }

    public function testChargeWithNoNonceOrPaymentMethod()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);
        $apiMock = $this->createMock(BraintreeGateway::class);
        $braintreeSuccessMock = $this->createMock(Successful::class);
        $transactionMock = $this->createMock(Transaction::class);
        $transactionGatewayMock = $this->createMock(TransactionGateway::class);

        $braintreeSuccessMock->transaction = $transactionMock;

        $apiMock
            ->method('transaction')
            ->willReturn($transactionGatewayMock);

        $transactionGatewayMock
            ->method('sale')
            ->willReturn($braintreeSuccessMock);

        $model = new ArrayObject([
            'amount' => 100,
        ]);

        $action = new TransactionSaleAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $transactionSaleRequest = new TransactionSaleRequest($model);

        $this->expectException(LogicException::class);

        $action->execute($transactionSaleRequest);
    }

    public function testChargeWithNoAmount()
    {
        $gatewayMock = $this->createMock(GatewayInterface::class);
        $apiMock = $this->createMock(BraintreeGateway::class);
        $braintreeSuccessMock = $this->createMock(Successful::class);
        $transactionMock = $this->createMock(Transaction::class);
        $transactionGatewayMock = $this->createMock(TransactionGateway::class);

        $braintreeSuccessMock->transaction = $transactionMock;

        $apiMock
            ->method('transaction')
            ->willReturn($transactionGatewayMock);

        $transactionGatewayMock
            ->method('sale')
            ->willReturn($braintreeSuccessMock);

        $model = new ArrayObject([
            'paymentMethodNonce' => '123456',
        ]);

        $action = new TransactionSaleAction();
        $action->setGateway($gatewayMock);
        $action->setApi($apiMock);

        $transactionSaleRequest = new TransactionSaleRequest($model);

        $this->expectException(LogicException::class);

        $action->execute($transactionSaleRequest);
    }
}