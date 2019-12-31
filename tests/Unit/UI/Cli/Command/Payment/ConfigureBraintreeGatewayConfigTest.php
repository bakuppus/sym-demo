<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Cli\Command\Payment;

use App\UI\Cli\Command\Payment\ConfigureBraintreeGatewayConfig;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigureBraintreeGatewayConfigTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    public function testCreatingSandbox()
    {
        $this->commandTester->setInputs([
            'y',
            'merhchantId',
            'publicKey',
            'privateKey',
        ]);

        $this->commandTester->execute([]);
    }

    public function testCreatingProduction()
    {
        $this->commandTester->setInputs([
            'n',
            'merhchantId',
            'publicKey',
            'privateKey',
        ]);

        $this->commandTester->execute([]);
    }

    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $application = new Application();
        $application->add(new ConfigureBraintreeGatewayConfig($entityManager));

        $command = $application->find('payment:gateway:braintree');

        $this->commandTester = new CommandTester($command);
    }
}