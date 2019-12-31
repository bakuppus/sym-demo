<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\MembershipCard;

use App\Application\Assigner\MembershipCard\ExpiresAt\MembershipCardExpiresAtAssignerInterface;
use App\Application\Assigner\MembershipCard\StartsAt\MembershipCardStartsAtAssignerInterface;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class MembershipCardAssignSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var MembershipCardStartsAtAssignerInterface */
    private $startsAtAssigner;

    /** @var MembershipCardExpiresAtAssignerInterface */
    private $expiresAtAssigner;

    public function __construct(
        MembershipCardStartsAtAssignerInterface $startsAtAssigner,
        MembershipCardExpiresAtAssignerInterface $expiresAtAssigner
    ) {
        $this->startsAtAssigner = $startsAtAssigner;
        $this->expiresAtAssigner = $expiresAtAssigner;
    }

    public function assign12MonthStartsAt(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (
            false === $subject instanceof MembershipCard
            || Membership::DURATION_12_MONTH !== $subject->getDurationType()
        ) {
            return;
        }

        $this->startsAtAssigner->assignStartsAtIfNotSet($subject);
    }

    public function assign12MonthExpiresAt(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (
            false === $subject instanceof MembershipCard
            || Membership::DURATION_12_MONTH !== $subject->getDurationType()
        ) {
            return;
        }

        $this->expiresAtAssigner->assignExpiresAtIfNotSet($subject);
    }

    public function assignCalendarYearStartsAt(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (
            false === $subject instanceof MembershipCard
            || Membership::DURATION_ANNUAL !== $subject->getDurationType()
        ) {
            return;
        }

        $this->startsAtAssigner->assignStartsAtIfNotSet($subject);
    }

    public function assignCalendarYearExpiresAt(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (
            false === $subject instanceof MembershipCard
            || Membership::DURATION_ANNUAL !== $subject->getDurationType()
        ) {
            return;
        }

        $this->expiresAtAssigner->assignExpiresAtIfNotSet($subject);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.membership_card.completed.pay' => [
                ['assign12MonthStartsAt', 10],
                ['assign12MonthExpiresAt', 9],
            ],
            'workflow.membership_card.completed.create' => [
                ['assignCalendarYearStartsAt', 10],
                ['assignCalendarYearExpiresAt', 9],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return MembershipCard|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }
}
