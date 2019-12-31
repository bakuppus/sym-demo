<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Order\Crm\Workflow;

use App\Application\Command\Order\Workflow\SellOrderCommand;
use App\Application\Command\Order\Workflow\SellOrderCommandHandler;
use App\Domain\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class SellOrderCommandTest extends TestCase
{
    public function testSellOrder(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $workflow = $this->createMock(WorkflowInterface::class);
        $workflow->expects($this->exactly(1))->method('apply');
        $registry = $this->createMock(Registry::class);
        $registry->expects($this->exactly(1))->method('get')->willReturn($workflow);

        $descriptor = new HandlerDescriptor(new SellOrderCommandHandler($entityManager, $registry));

        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);

        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $order = new Order();

        $command = new SellOrderCommand();
        $command->populate([AbstractNormalizer::OBJECT_TO_POPULATE => $order]);

        $bus->dispatch($command);
    }
}
