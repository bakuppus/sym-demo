<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\MembershipCard;

use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Promotion\Notification\NewMembershipCardNotification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;

class MembershipCardSendEmailSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.membership_card.completed.create' => 'onCompletedCreate',
        ];
    }

    public function onCompletedCreate(Event $event): void
    {
        /** @var MembershipCard $subject */
        $subject = $event->getSubject();

        if (false === $subject instanceof MembershipCard) {
            return;
        }

        $this->messageBus->dispatch(new Envelope(new NewMembershipCardNotification($subject)));
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
