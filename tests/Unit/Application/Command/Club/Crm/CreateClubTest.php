<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Club\Crm;

use App\Application\Command\Club\Crm\CreateClub\CreateClubCommand;
use App\Application\Command\Club\Crm\CreateClub\CreateClubCommandHandler;
use App\Tests\Fixtures\Club\CreateClub\CreateClubCommandFixture;
use App\Tests\Fixtures\FixtureLoaderTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class CreateClubTest extends KernelTestCase
{
    use FixtureLoaderTrait;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
    }

    /**
     * @dataProvider createClubCommandDataProvider
     *
     * @param CreateClubCommand $command
     *
     * @throws Exception
     */
    public function testCreateClub(CreateClubCommand $command): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new CreateClubCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $envelope = new Envelope($command);

        $bus = new MessageBus([$middleware]);
        $bus->dispatch($envelope);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function createClubCommandDataProvider(): array
    {
        $data = $this->loadFixture(new CreateClubCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, CreateClubCommand::class, 'json');

        return [[$command]];
    }
}
