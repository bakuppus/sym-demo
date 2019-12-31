<?php

declare(strict_types=1);

namespace App\Application\Command\Order\CreateOrder;

use App\Domain\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class CreateOrderCommandHandler implements MessageHandlerInterface
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

    public function __invoke(CreateOrderCommand $command): Order
    {
        $resource = $command->getResource();

        $this->entityManager->persist($resource);

        $this->workflow->get($resource, Order::GRAPH)
            ->apply($resource, Order::TRANSITION_CREATE);

        return $resource;
    }
}
