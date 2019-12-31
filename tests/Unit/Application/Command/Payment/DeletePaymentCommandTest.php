<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Payment;

use App\Application\Command\Payment\DeletePayment\DeletePaymentCommand;
use App\Application\Command\Payment\DeletePayment\DeletePaymentCommandHandler;
use App\Domain\Order\OrderMembership;
use App\Domain\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DeletePaymentCommandTest extends TestCase
{
    public function testSuccess()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $order = $this->createMock(OrderMembership::class);
        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount(1000);
        $payment->setCurrencyCode(Payment::CURRENCY_SEK);
        $payment->setDetails([]);

        $command = new DeletePaymentCommand($payment);

        $handler = new DeletePaymentCommandHandler($entityManager);

        $result = $handler->__invoke($command);

        $this->assertEquals($result, null);
    }
}
