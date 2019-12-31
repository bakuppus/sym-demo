<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\UpdateMembershipFee\UpdateMembershipFeeCommand;
use App\Application\Command\Promotion\Crm\UpdateMembershipFee\UpdateMembershipFeeCommandHandler;
use App\Domain\Promotion\MembershipFee;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class UpdateMembershipFeeTest extends TestCase
{
    public function testUpdateMembershipFee(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $descriptor = new HandlerDescriptor(new UpdateMembershipFeeCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $command = new UpdateMembershipFeeCommand();
        $command->vat = 1;
        $command->price = 100;
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => new MembershipFee()]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }
}
