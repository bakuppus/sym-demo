<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\Binding\CommandBindDoctrineDriver;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Infrastructure\Shared\Command\Binding\Event\CommandBindSubscriber;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class CommandBindingTest extends TestCase
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var Reader */
    private $reader;

    /** @var ViewEvent */
    private $event;

    /** @var ObjectRepository */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->event = $this->createMock(ViewEvent::class);
        $this->reader = $this->createMock(Reader::class);
        $this->repository = $this->createMock(ObjectRepository::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
    }

    public function testSuccessfulSingleBinding(): void
    {
        $dummyCommand = new DummyCommandWithSingleBinding();
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);

        $configuration = new CommandBind();
        $configuration->targetEntity = DummyEntity::class;
        $this->reader->expects($this->exactly(1))->method('getPropertyAnnotation')->willReturn($configuration);

        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn(new DummyEntity());
        $this->em->expects($this->once())->method('getRepository')->willReturn($this->repository);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);

        $this->assertInstanceOf(DummyEntity::class, $dummyCommand->dummy);
        $this->assertInternalType('array', CommandBindSubscriber::getSubscribedEvents());
    }

    public function testSuccessfulTraverseBinding(): void
    {
        $dummyCommand = new DummyCommandTraverseBinding();
        $dummyCommand->traverse = new DummyCommandWithSingleBinding();
        $dummyCommand->collection = [new DummyCommandWithSingleBinding()];
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);

        $configuration = new CommandBind();
        $configuration->isTraverse = true;
        $configuration2 = new CommandBind();
        $configuration2->targetEntity = DummyEntity::class;
        $configuration3 = new CommandBind();
        $configuration3->isTraverse = true;
        $configuration4 = new CommandBind();
        $configuration4->targetEntity = DummyEntity::class;
        $this->reader->expects($this->exactly(4))->method('getPropertyAnnotation')
            ->willReturn($configuration, $configuration2, $configuration3, $configuration4);

        $this->repository->expects($this->exactly(2))
            ->method('find')
            ->willReturn(new DummyEntity());
        $this->em->expects($this->exactly(2))->method('getRepository')->willReturn($this->repository);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);

        $this->assertInstanceOf(DummyEntity::class, $dummyCommand->traverse->dummy);
        $this->assertInternalType('array', CommandBindSubscriber::getSubscribedEvents());
    }

    public function testSuccessfulMultipleBinding(): void
    {
        $dummyCommand = new DummyCommandWithMultipleBinding();
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);

        $configuration1 = new CommandBind();
        $configuration1->targetEntity = DummyEntity::class;
        $configuration2 = new CommandBind();
        $configuration2->targetEntity = DummyEntity2::class;
        $configuration3 = new CommandBind();
        $configuration3->targetEntity = DummyEntity3::class;
        $this->reader->expects($this->exactly(3))->method('getPropertyAnnotation')
            ->willReturn($configuration1, $configuration2, $configuration3);

        $this->repository->expects($this->exactly(3))->method('find')
            ->willReturn(new DummyEntity(), new DummyEntity2(), new DummyEntity3());
        $this->em->expects($this->exactly(3))->method('getRepository')->willReturn($this->repository);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);

        $this->assertInstanceOf(DummyEntity::class, $dummyCommand->dummy1);
        $this->assertInstanceOf(DummyEntity2::class, $dummyCommand->dummy2);
        $this->assertInstanceOf(DummyEntity3::class, $dummyCommand->dummy3);
        $this->assertInternalType('array', CommandBindSubscriber::getSubscribedEvents());
    }

    public function testNullableConfiguration(): void
    {
        $dummyCommand = new DummyCommandWithSingleBinding();
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);
        $this->reader->expects($this->exactly(1))->method('getPropertyAnnotation')->willReturn(null);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);

        $this->assertEquals(1, $dummyCommand->dummy);
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testEntityNotFound(): void
    {
        $dummyCommand = new DummyCommandWithSingleBinding();
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);

        $configuration = new CommandBind();
        $configuration->targetEntity = DummyEntity::class;
        $this->reader->expects($this->once())->method('getPropertyAnnotation')->willReturn($configuration);

        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn(null);
        $this->em->expects($this->once())->method('getRepository')->willReturn($this->repository);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);
    }

    public function testSubscriberCommandAwareInterface()
    {
        $dummyCommand = new stdClass();
        $this->event->expects($this->once())->method('getControllerResult')->willReturn($dummyCommand);

        $driver = new CommandBindDoctrineDriver($this->reader, $this->em);
        $subscriber = new CommandBindSubscriber($driver);
        $subscriber->bind($this->event);
    }
}
