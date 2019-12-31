<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Payment;

use App\Application\Command\Payment\UpdatePayment\UpdatePaymentCommand;
use App\Application\Command\Payment\UpdatePayment\UpdatePaymentCommandHandler;
use App\Domain\Order\OrderMembership;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdatePaymentCommandTest extends TestCase
{
    public function testSuccess()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $order = $this->createMock(OrderMembership::class);
        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount(1000);
        $payment->setCurrencyCode(Payment::CURRENCY_SEK);
        $payment->setDetails([]);

        $order = $this->createMock(OrderMembership::class);
        $amount = 2000;
        $currencyCode = Payment::CURRENCY_SEK;
        $details = [
            'transaction' => [
                'id' => 'blabla1',
            ],
        ];

        $command = new UpdatePaymentCommand($payment);
        $command->setOrder($order);
        $command->setAmount($amount);
        $command->setCurrencyCode($currencyCode);
        $command->setDetails($details);

        $handler = new UpdatePaymentCommandHandler($entityManager);

        $result = $handler->__invoke($command);

        $this->assertInstanceOf(PaymentInterface::class, $result);
        $this->assertEquals($result->getOrder(), $order);
        $this->assertEquals($result->getAmount(), $amount);
        $this->assertEquals($result->getCurrencyCode(), $currencyCode);
        $this->assertEquals($result->getDetails(), $details);
    }
}
