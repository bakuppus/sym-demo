<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Cli\Command;

use App\UI\Cli\Command\InitializeFeeUnitsCommand;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InitializeFeeUnitsCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    public function testInitializeFeeUnits()
    {
        $this->commandTester->execute([]);
    }

    protected function setUp(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->any())->method('persist');
        $entityManagerMock->expects($this->once())->method('flush');

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->any())->method('findOneBy')->willReturn(null);
        $entityManagerMock->expects($this->any())->method('getRepository')->willReturn($repository);

        $application = new Application();
        $application->add(new InitializeFeeUnitsCommand($entityManagerMock));

        $command = $application->find('fee-units:init');

        $this->commandTester = new CommandTester($command);
    }
}
