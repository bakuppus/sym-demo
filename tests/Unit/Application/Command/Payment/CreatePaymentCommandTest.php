<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Payment;

use App\Application\Command\Payment\CreatePayment\CreatePaymentCommand;
use App\Application\Command\Payment\CreatePayment\CreatePaymentCommandHandler;
use App\Domain\Order\OrderMembership;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

class CreatePaymentCommandTest extends TestCase
{
    public function testSuccess()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $workflow = $this->createMock(WorkflowInterface::class);
        $workflow->expects($this->exactly(1))->method('apply');
        $registry = $this->createMock(Registry::class);
        $registry->expects($this->exactly(1))->method('get')->willReturn($workflow);

        $order = $this->createMock(OrderMembership::class);
        $amount = 2000;
        $currencyCode = Payment::CURRENCY_SEK;
        $details = [
            'transaction' => [
                'id' => 'blabla1',
            ],
        ];

        $command = new CreatePaymentCommand();
        $command->setOrder($order);
        $command->setAmount($amount);
        $command->setCurrencyCode($currencyCode);
        $command->setDetails($details);

        $handler = new CreatePaymentCommandHandler($entityManager, $registry);

        $result = $handler->__invoke($command);

        $this->assertInstanceOf(PaymentInterface::class, $result);
        $this->assertEquals($result->getOrder(), $order);
        $this->assertEquals($result->getAmount(), $amount);
        $this->assertEquals($result->getCurrencyCode(), $currencyCode);
        $this->assertEquals($result->getDetails(), $details);
        $this->assertEquals($result->getState(), Payment::STATE_INIT);
    }
}
