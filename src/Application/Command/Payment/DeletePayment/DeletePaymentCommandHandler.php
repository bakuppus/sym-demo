<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\DeletePayment;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeletePaymentCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(DeletePaymentCommand $command)
    {
        $payment = $command->getResource();

        $this->entityManager->remove($payment);
        $this->entityManager->flush();

        return null;
    }
}