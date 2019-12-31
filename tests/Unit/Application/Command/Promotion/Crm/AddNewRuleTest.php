<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\AddNewAction\AddNewActionCommandHandler;
use App\Application\Command\Promotion\Crm\AddNewRule\AddNewRuleCommandHandler;
use App\Domain\Promotion\PromotionRule;
use App\Tests\Fixtures\Promotion\Rule\AddNewRule\AddNewRuleCommandFixture;
use App\Tests\Fixtures\FixtureLoaderTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Serializer\SerializerInterface;
use App\Application\Command\Promotion\Crm\AddNewRule\AddNewRuleCommand;
use App\Domain\Promotion\Promotion;

class AddNewRuleTest extends KernelTestCase
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
     * @dataProvider addNewRuleCommandDataProvider
     * @param AddNewRuleCommand $command
     */
    public function testAddNewRule(AddNewRuleCommand $command): void
    {
        $promotion = new Promotion();

        $command->populate(['object_to_populate' => $promotion]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new AddNewRuleCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $promotion = new Promotion();
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => $promotion]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }

    public function addNewRuleCommandDataProvider(): array
    {
        $data = $this->loadFixture(new AddNewRuleCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, AddNewRuleCommand::class, 'json');

        return [[$command]];
    }
}