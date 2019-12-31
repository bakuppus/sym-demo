<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Order\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Order\UpdateOrder\UpdateOrderCommand;
use App\Application\Command\Order\UpdateOrder\UpdateOrderCommandHandler;
use App\Domain\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class UpdateOrderTest extends TestCase
{
    public function testUpdateOrder(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');
        $descriptor = new HandlerDescriptor(new UpdateOrderCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $command = new UpdateOrderCommand();
        $command->currencyCode = 'sek';
        $command->localCode = 'se';
        $command->notes = 'Voluptatem necessitatibus aspernatur iure voluptatem qui quisquam maiores officia.';
        $command->paymentState = 'new';
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => new Order()]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }
}
