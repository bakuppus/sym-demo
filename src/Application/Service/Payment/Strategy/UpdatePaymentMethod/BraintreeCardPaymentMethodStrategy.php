<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\UpdatePaymentMethod;

use App\Application\Command\Payment\UpdatePayment\UpdatePaymentCommand;
use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Domain\Payment\GatewayConfig;
use App\Domain\Payment\PaymentMethod;
use App\Infrastructure\Payment\Utils\BraintreeGatewayNameGetterTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class BraintreeCardPaymentMethodStrategy implements UpdatePaymentMethodStrategyInterface
{
    use BraintreeGatewayNameGetterTrait;

    public const TYPE = 'braintree_card';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ParameterBagInterface */
    private $params;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        ParameterBagInterface $params
    ) {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->params = $params;
    }

    public function updatePaymentMethod(PaymentInterface $payment): PaymentInterface
    {
        try {
            $isSandbox = $this->params->get('braintree_sandbox');
        } catch (ParameterNotFoundException $e) {
            throw new PaymentLogicException($e->getMessage());
        }

        $gatewayConfigRepository = $this->entityManager->getRepository(GatewayConfig::class);
        $gatewayConfig = $gatewayConfigRepository->findOneBy([
            'gatewayName' => $this->getBraintreeGatewayName($isSandbox),
        ]);

        if (null === $gatewayConfig) {
            throw new PaymentLogicException("Braintree gateway config not found");
        }

        $paymentMethodRepository = $this->entityManager->getRepository(PaymentMethod::class);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $paymentMethodRepository->findOneBy([
            'code' => PaymentMethod::CODE_CARD,
        ]);

        if (null === $paymentMethod) {
            throw new PaymentLogicException("Braintree card payment method not found");
        }

        $updatePaymentCommand = new UpdatePaymentCommand($payment);
        $updatePaymentCommand->setOrder($payment->getOrder());
        $updatePaymentCommand->setAmount($payment->getAmount());
        $updatePaymentCommand->setCurrencyCode($payment->getCurrencyCode());
        $updatePaymentCommand->setDetails($payment->getDetails());
        $updatePaymentCommand->setPaymentMethod($paymentMethod);

        $this->messageBus->dispatch($updatePaymentCommand);

        return $payment;
    }

    public function supports(string $methodName): bool
    {
        return self::TYPE === $methodName;
    }
}
