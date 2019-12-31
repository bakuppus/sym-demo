<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Payment\Payum\Braintree\Action;

use App\Infrastructure\Payment\Payum\Braintree\Action\StatusAction;
use Braintree\Transaction;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\GetHumanStatus;
use PHPUnit\Framework\TestCase;

class StatusActionTest extends TestCase
{
    public function testSuccessfulChargeStatus()
    {
        $model = [
            'transaction' => [
                'type' => Transaction::SALE,
                'status' => Transaction::SUBMITTED_FOR_SETTLEMENT,
            ],
        ];

        $gatewayMock = $this->createMock(GatewayInterface::class);

        $statusAction = new StatusAction();
        $statusAction->setGateway($gatewayMock);

        $getHumanStatusRequest = new GetHumanStatus($model);
        $statusAction->execute($getHumanStatusRequest);

        $status = $getHumanStatusRequest->getValue();

        $this->assertEquals(GetHumanStatus::STATUS_CAPTURED, $status);
    }

    public function testSuccessfulRefundStatus()
    {
        $model = [
            'transaction' => [
                'type' => Transaction::CREDIT,
                'status' => Transaction::SUBMITTED_FOR_SETTLEMENT,
            ],
        ];

        $gatewayMock = $this->createMock(GatewayInterface::class);

        $statusAction = new StatusAction();
        $statusAction->setGateway($gatewayMock);

        $getHumanStatusRequest = new GetHumanStatus($model);
        $statusAction->execute($getHumanStatusRequest);

        $status = $getHumanStatusRequest->getValue();

        $this->assertEquals(GetHumanStatus::STATUS_REFUNDED, $status);
    }
}