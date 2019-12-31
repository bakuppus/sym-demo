<?php

declare(strict_types=1);

namespace App\Application\Service\Payment\Strategy\UpdatePaymentMethod;

use App\Application\Command\Payment\UpdatePayment\UpdatePaymentCommand;
use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Domain\Payment\GatewayConfig;
use App\Domain\Payment\PaymentMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OnSitePaymentMethodStrategy implements UpdatePaymentMethodStrategyInterface
{
    public const TYPE = 'on_site';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function updatePaymentMethod(PaymentInterface $payment): PaymentInterface
    {
        $gatewayConfigRepository = $this->entityManager->getRepository(GatewayConfig::class);
        $gatewayConfig = $gatewayConfigRepository->findOneBy([
            'gatewayName' => GatewayConfig::GATEWAY_NAME_OFFLINE,
        ]);

        if (null === $gatewayConfig) {
            throw new PaymentLogicException("Offline gateway config not found");
        }

        $paymentMethodRepository = $this->entityManager->getRepository(PaymentMethod::class);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $paymentMethodRepository->findOneBy([
            'code' => PaymentMethod::CODE_ON_SITE,
        ]);

        if (null === $paymentMethod) {
            throw new PaymentLogicException("On site payment method not found");
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