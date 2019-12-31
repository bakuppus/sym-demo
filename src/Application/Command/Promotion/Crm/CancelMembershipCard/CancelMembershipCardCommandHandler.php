<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\CancelMembershipCard;

use App\Domain\Promotion\MembershipCard;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class CancelMembershipCardCommandHandler implements MessageHandlerInterface
{
    /** @var Registry */
    private $workflow;

    /**
     * CancelMembershipCardCommandHandler constructor.
     *
     * @param Registry $workflow
     */
    public function __construct(Registry $workflow)
    {
        $this->workflow = $workflow;
    }

    public function __invoke(CancelMembershipCardCommand $command): MembershipCard
    {
        $resource = $command->getResource();

        $this->workflow->get($resource, MembershipCard::STATUS_WORKFLOW_NAME)
            ->apply($resource, MembershipCard::STATUS_TRANSITION_TO_OLD);

        $this->workflow->get($resource, MembershipCard::WORKFLOW_NAME)
            ->apply($resource, MembershipCard::TRANSITION_CANCEL);

        return $resource;
    }
}
