<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\AddNewMembershipFee\AddNewMembershipFeeCommand;
use App\Application\Command\Promotion\Crm\AddNewMembershipFee\AddNewMembershipFeeCommandHandler;
use App\Domain\Accounting\FeeUnit;
use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class AddNewMembershipFeeTest extends TestCase
{
    public function testCreateMembershipFee(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $descriptor = new HandlerDescriptor(new AddNewMembershipFeeCommandHandler($entityManager));

        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);

        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $command = new AddNewMembershipFeeCommand();
        $command->feeUnit = new FeeUnit();
        $command->price = 100;
        $command->vat = 10;
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => new Membership()]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }
}
