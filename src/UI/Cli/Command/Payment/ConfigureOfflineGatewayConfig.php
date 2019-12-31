<?php

declare(strict_types=1);

namespace App\UI\Cli\Command\Payment;

use App\Domain\Payment\GatewayConfig;
use App\Domain\Payment\PaymentMethod;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureOfflineGatewayConfig extends Command
{
    protected static $defaultName = 'payment:gateway:offline';

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Offline gateway config configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gatewayConfig = new GatewayConfig();
        $gatewayConfig->setGatewayName(GatewayConfig::GATEWAY_NAME_OFFLINE);
        $gatewayConfig->setFactoryName(GatewayConfig::FACTORY_NAME_OFFLINE);
        $gatewayConfig->setConfig([]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setCode(PaymentMethod::CODE_ON_SITE);
        $paymentMethod->setIsEnabled(true);

        $this->em->persist($paymentMethod);

        $gatewayConfig->addPaymentMethod($paymentMethod);

        $this->em->persist($gatewayConfig);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $output->writeln('Offline gateway config already exists');

            return 1;
        }

        return 0;
    }
}