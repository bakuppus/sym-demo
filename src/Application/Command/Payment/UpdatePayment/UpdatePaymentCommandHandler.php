<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\UpdatePayment;

use App\Domain\Payment\Core\PaymentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdatePaymentCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdatePaymentCommand $command): PaymentInterface
    {
        $payment = $command->getResource();

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $payment;
    }
}