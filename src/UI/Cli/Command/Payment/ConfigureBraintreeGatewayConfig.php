<?php

declare(strict_types=1);

namespace App\UI\Cli\Command\Payment;

use App\Domain\Payment\GatewayConfig;
use App\Domain\Payment\PaymentMethod;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Payum\Core\Bridge\Defuse\Security\DefuseCypher;

class ConfigureBraintreeGatewayConfig extends Command
{
    protected static $defaultName = 'payment:gateway:braintree';

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Braintree gateway config configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('Do you want to add sandbox credentials (y/n): ');
        $isSandbox = $helper->ask($input, $output, $question);

        $question = new Question('Enter merchant id: ');
        $merchantId = (string) $helper->ask($input, $output, $question);

        $question = new Question('Enter public key: ');
        $publicKey = (string) $helper->ask($input, $output, $question);

        $question = new Question('Enter private key: ');
        $privateKey = (string) $helper->ask($input, $output, $question);

        $gatewayName = $isSandbox ?
            BraintreeGateway::SANDBOX_GATEWAY_NAME :
            BraintreeGateway::PRODUCTION_GATEWAY_NAME;

        $cypher = new DefuseCypher($_ENV['PAYUM_CYPHER_KEY']);

        $gatewayConfig = new GatewayConfig();
        $gatewayConfig->setGatewayName($gatewayName);
        $gatewayConfig->setFactoryName(BraintreeGateway::GATEWAY_FACTORY_NAME);
        $gatewayConfig->setConfig([
            'environment' => $isSandbox ?
                BraintreeGateway::ENVIRONMENT_SANDBOX :
                BraintreeGateway::ENVIRONMENT_PRODUCTION,
            'merchant_id' => $merchantId,
            'public_key' => $publicKey,
            'private_key' => $privateKey,
        ]);
        $gatewayConfig->encrypt($cypher);

        $paymentMethodCard = new PaymentMethod();
        $paymentMethodCard->setCode(PaymentMethod::CODE_CARD);
        $paymentMethodCard->setEnvironment($isSandbox ?
            PaymentMethod::ENVIRONMENT_SANDBOX :
            PaymentMethod::ENVIRONMENT_PRODUCTION);
        $paymentMethodCard->setIsEnabled(true);

        $this->em->persist($paymentMethodCard);

        $gatewayConfig->addPaymentMethod($paymentMethodCard);

        $this->em->persist($gatewayConfig);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $output->writeln('Braintree gateway config already exists');

            return 1;
        }

        return 0;
    }
}