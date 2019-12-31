<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\AddNewRule\AddNewRuleCommandHandler;
use App\Application\Command\Promotion\Crm\UpdateAction\UpdateActionCommandHandler;
use App\Domain\Club\Club;
use App\Domain\Promotion\Promotion;
use App\Tests\Fixtures\FixtureLoaderTrait;
use App\Domain\Promotion\PromotionAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Serializer\SerializerInterface;
use App\Tests\Fixtures\Promotion\Action\UpdateAction\UpdateActionCommandFixture;
use App\Application\Command\Promotion\Crm\UpdateAction\UpdateActionCommand;
use Exception;
use ReflectionException;

class UpdateActionTest extends KernelTestCase
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
     * @dataProvider updateActionCommandDataProvider
     * @param UpdateActionCommand $command
     *
     * @throws Exception
     */
    public function testUpdateAction(UpdateACtionCommand $command): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new UpdateActionCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $club = new Club();
        $promotion = new Promotion();
        $promotion->setClub($club);

        $rule = new PromotionAction();
        $rule->setId(1);
        $rule->setType('booking_discount_percentage');
        $rule->setConfiguration(['percentage' => 5]);
        $rule->setPromotion($promotion);

        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => $rule]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    public function updateActionCommandDataProvider(): array
    {
        $data = $this->loadFixture(new UpdateActionCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, UpdateActionCommand::class, 'json');

        return [[$command]];
    }
}