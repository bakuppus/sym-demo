<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Order\Crm;

use App\Application\Command\Order\CreateOrder\CreateOrderCommand;
use App\Application\Command\Order\CreateOrder\CreateOrderCommandHandler;
use App\Application\Command\Order\CreateOrder\Item\CreateOrderItemCommand;
use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Domain\Player\Player;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class CreateOrderTest extends TestCase
{
    public function testCreateOrder(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $workflow = $this->createMock(WorkflowInterface::class);
        $workflow->expects($this->exactly(1))->method('apply');
        $registry = $this->createMock(Registry::class);
        $registry->expects($this->exactly(1))->method('get')->willReturn($workflow);

        $descriptor = new HandlerDescriptor(new CreateOrderCommandHandler($entityManager, $registry));

        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);

        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $itemCommand = new CreateOrderItemCommand();
        $itemCommand->total = 100;
        $itemCommand->quantity = 1;

        $command = new CreateOrderCommand();
        $command->club = new Club();
        $command->course = new Course();
        $command->customer = new Player();
        $command->items = [$itemCommand];
        $command->currencyCode = 'sek';
        $command->localeCode = 'se';
        $command->notes = 'Voluptatem necessitatibus aspernatur iure voluptatem qui quisquam maiores officia.';

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }
}
