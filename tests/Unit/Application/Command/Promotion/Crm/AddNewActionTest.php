<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use App\Application\Command\Promotion\Crm\AddNewAction\AddNewActionCommand;
use App\Application\Command\Promotion\Crm\AddNewAction\AddNewActionCommandHandler;
use App\Domain\Promotion\Promotion;
use App\Tests\Fixtures\FixtureLoaderTrait;
use App\Tests\Fixtures\Promotion\Action\AddNewAction\AddNewActionCommandFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Serializer\SerializerInterface;
use ReflectionException;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;

class AddNewActionTest extends KernelTestCase
{
    use FixtureLoaderTrait;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
    }

    /**
     * @dataProvider addNewActionCommandDataProvider
     * @param AddNewActionCommand $command
     */
    public function testAddNewAction(AddNewActionCommand $command): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new AddNewActionCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $promotion = new Promotion();
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => $promotion]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    public function addNewActionCommandDataProvider(): array
    {
        $data = $this->loadFixture(new AddNewActionCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, AddNewActionCommand::class, 'json');

        return [[$command]];
    }
}