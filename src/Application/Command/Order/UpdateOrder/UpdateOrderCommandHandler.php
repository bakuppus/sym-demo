<?php

declare(strict_types=1);

namespace App\Application\Command\Order\UpdateOrder;

use App\Domain\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateOrderCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateOrderCommand $command): Order
    {
        $source = $command->getResource();

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
