<?php

declare(strict_types=1);

namespace App\Application\Command\Order\Workflow;

use App\Domain\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class SellOrderCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Registry */
    private $workflow;

    public function __construct(EntityManagerInterface $entityManager, Registry $workflow)
    {
        $this->entityManager = $entityManager;
        $this->workflow = $workflow;
    }

    public function __invoke(SellOrderCommand $command): Order
    {
        $subject = $command->getResource();

        $this->entityManager->persist($subject);

        $this->workflow->get($subject, Order::PAYMENT_GRAPH)->apply($subject, $command->transition);

        return $subject;
    }
}
