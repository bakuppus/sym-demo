<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\MarkAsPaidMembershipCard;

use App\Domain\Promotion\MembershipCard;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;
use LogicException;

final class MarkAsPaidMembershipCardCommandHandler implements MessageHandlerInterface
{
    /** @var Registry */
    private $workflow;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * MarkAsPaidMembershipCardCommandHandler constructor.
     *
     * @param Registry $workflow
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Registry $workflow, EntityManagerInterface $entityManager)
    {
        $this->workflow = $workflow;
        $this->entityManager = $entityManager;
    }

    public function __invoke(MarkAsPaidMembershipCardCommand $command): MembershipCard
    {
        $resource = $command->getResource();
        $resource->setIsManuallyPaid(true);

        $this->entityManager->persist($resource);

        $this->workflow->get($resource, MembershipCard::STATUS_WORKFLOW_NAME)
            ->apply($resource, MembershipCard::STATUS_TRANSITION_TO_UPCOMING);

        $this->workflow->get($resource, MembershipCard::WORKFLOW_NAME)
            ->apply($resource, MembershipCard::TRANSITION_PAY);

        return $resource;
    }
}
