<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddCardToMembership;

use App\Domain\Promotion\MembershipCard;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class AddCardToMembershipCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Registry */
    private $workflow;

    /**
     * AddCardToMembershipCommandHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Registry $workflow
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Registry $workflow
    ) {
        $this->entityManager = $entityManager;
        $this->workflow = $workflow;
    }

    public function __invoke(AddCardToMembershipCommand $command): MembershipCard
    {
        $resource = $command->getResource();
        $this->entityManager->persist($resource);

        $this->workflow->get($resource, MembershipCard::STATUS_WORKFLOW_NAME)
            ->apply($resource, MembershipCard::STATUS_TRANSITION_TO_FUTURE);

        $this->workflow->get($resource, MembershipCard::WORKFLOW_NAME)
            ->apply($resource, MembershipCard::TRANSITION_CREATE);

        return $resource;
    }
}
