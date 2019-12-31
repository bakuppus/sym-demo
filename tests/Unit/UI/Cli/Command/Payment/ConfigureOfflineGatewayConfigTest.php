<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Cli\Command\Payment;

use App\UI\Cli\Command\Payment\ConfigureOfflineGatewayConfig;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigureOfflineGatewayConfigTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $application = new Application();
        $application->add(new ConfigureOfflineGatewayConfig($entityManager));

        $command = $application->find('payment:gateway:offline');

        $this->commandTester = new CommandTester($command);
    }

    public function testSuccess()
    {
        $this->commandTester->execute([]);
    }
}