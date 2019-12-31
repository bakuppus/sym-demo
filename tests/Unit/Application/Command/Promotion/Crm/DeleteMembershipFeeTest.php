<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Order\Crm;

use ApiPlatform\Core\Bridge\Symfony\Messenger\RemoveStamp;
use App\Domain\Promotion\MembershipFee;
use App\Infrastructure\Shared\Command\DeleteCommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Workflow\Registry;

final class DeleteMembershipFeeTest extends TestCase
{
    public function testDeleteMembershipFee(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $registry = $this->createMock(Registry::class);

        $descriptor = new HandlerDescriptor(new DeleteCommandHandler($entityManager, $registry));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $fee = new MembershipFee();
        $fee->setId(1);

        $envelope = new Envelope($fee);
        $envelope->with(new RemoveStamp());

        $bus->dispatch($envelope);
    }
}
