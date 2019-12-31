<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\PublishMembership;

use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class PublishMembershipCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Registry */
    private $stateMachineRegistry;

    public function __construct(
        EntityManagerInterface $entityManager,
        Registry $stateMachineRegistry
    ) {
        $this->entityManager = $entityManager;
        $this->stateMachineRegistry = $stateMachineRegistry;
    }

    public function __invoke(PublishMembershipCommand $command): Membership
    {
        $membership = $command->getResource();

        $stateMachine = $this->stateMachineRegistry->get($membership, Membership::GRAPH);
        $stateMachine->apply($membership, Membership::TRANSITION_PUBLISH);

        $this->entityManager->persist($membership);

        return $command->getResource();
    }
}
