<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\AddCardToMembership\AddCardToMembershipCommand;
use App\Application\Command\Promotion\Crm\AddCardToMembership\AddCardToMembershipCommandHandler;
use App\Domain\Club\Club;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class AddCardToMembershipTest extends TestCase
{
    public function testAddCardToMembership(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $workflow = $this->createMock(WorkflowInterface::class);
        $workflow->expects($this->exactly(2))->method('apply');

        $registry = $this->createMock(Registry::class);
        $registry->expects($this->exactly(2))->method('get')->willReturn($workflow);

        $descriptor = new HandlerDescriptor(
            new AddCardToMembershipCommandHandler($entityManager, $registry)
        );

        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);

        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $command = new AddCardToMembershipCommand();
        $command->player = new Player();
        $command->club = new Club();
        $command->durationType = Membership::DURATION_ANNUAL;
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => new Membership()]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }
}
