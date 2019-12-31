<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Promotion\Crm;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Promotion\Crm\UpdateAction\UpdateActionCommandHandler;
use App\Application\Command\Promotion\Crm\UpdateRule\UpdateRuleCommand;
use App\Domain\Club\Club;
use App\Domain\Promotion\Promotion;
use App\Domain\Promotion\PromotionAction;
use App\Tests\Fixtures\FixtureLoaderTrait;
use App\Tests\Fixtures\Promotion\Rule\UpdateRule\UpdateRuleCommandFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Serializer\SerializerInterface;
use App\Application\Command\Promotion\Crm\UpdateRule\UpdateRuleCommandHandler;
use App\Domain\Promotion\PromotionRule;
use Exception;
use ReflectionException;

class UpdateRuleTest extends KernelTestCase
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
     * @dataProvider updateRuleCommandDataProvider
     * @param UpdateRuleCommand $command
     *
     * @throws Exception
     */
    public function testUpdateRule(UpdateRuleCommand $command): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $descriptor = new HandlerDescriptor(new UpdateRuleCommandHandler($entityManager));
        $locator = $this->createMock(HandlersLocator::class);
        $locator->expects($this->atLeastOnce())->method('getHandlers')->willReturn([$descriptor]);
        $middleware = new HandleMessageMiddleware($locator);

        $bus = new MessageBus([$middleware]);

        $club = new Club();
        $promotion = new Promotion();
        $promotion->setClub($club);

        $rule = new PromotionRule();
        $rule->setId(1);
        $rule->setType('days_in_week_checker');
        $rule->setPromotion($promotion);
        $rule->setConfiguration(['Wednesday', 'Sunday']);

        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => $rule]);

        $envelope = new Envelope($command);

        $bus->dispatch($envelope);
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    public function updateRuleCommandDataProvider(): array
    {
        $data = $this->loadFixture(new UpdateRuleCommandFixture());

        $command = self::$kernel->getContainer()
            ->get('serializer')->deserialize($data, UpdateRuleCommand::class, 'json');

        return [[$command]];
    }
}
