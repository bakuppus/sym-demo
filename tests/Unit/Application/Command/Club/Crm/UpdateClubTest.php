<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Club\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Club\Crm\UpdateClub\UpdateClubCommand;
use App\Application\Command\Club\Crm\UpdateClub\UpdateClubCommandHandler;
use App\Domain\Club\Club;
use App\Tests\Fixtures\Club\UpdateClub\UpdateClubCommandFixture;
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

final class UpdateClubTest extends KernelTestCase
{
    use FixtureLoaderTrait;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
    }

    /**
     * @dataProvider updateClubCommandDataProvider
     *
     * @param UpdateClubCommand $command
     *
     * @throws Exception
     */
    public function testUpdateClub(UpdateClubCommand $command): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new UpdateClubCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => new Club()]);

        $envelope = new Envelope($command);

        $bus = new MessageBus([$middleware]);
        $bus->dispatch($envelope);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function updateClubCommandDataProvider(): array
    {
        $data = $this->loadFixture(new UpdateClubCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, UpdateClubCommand::class, 'json');

        return [[$command]];
    }
}
