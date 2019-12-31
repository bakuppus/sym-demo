<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\MembershipCard;

use App\Domain\Promotion\MembershipCard;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MembershipCardDoctrineEventSubscriber implements EventSubscriber
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postUpdate,
            Events::postPersist,
            Events::postRemove,
            SoftDeleteableListener::POST_SOFT_DELETE,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $membershipCard = $args->getObject();
        if (!$membershipCard instanceof MembershipCard) {
            return;
        }

        $this->eventDispatcher->dispatch(new MembershipCardHandleRelatedGolfClubEvent($membershipCard));
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $membershipCard = $args->getObject();
        if (!$membershipCard instanceof MembershipCard) {
            return;
        }

        $this->eventDispatcher->dispatch(new MembershipCardHandleRelatedGolfClubEvent($membershipCard));
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $membershipCard = $args->getObject();
        if (!$membershipCard instanceof MembershipCard) {
            return;
        }

        $this->eventDispatcher->dispatch(new MembershipCardHandleRelatedGolfClubEvent($membershipCard));
    }

    public function postSoftDelete(LifecycleEventArgs $args): void
    {
        $membershipCard = $args->getObject();
        if (!$membershipCard instanceof MembershipCard) {
            return;
        }

        $this->eventDispatcher->dispatch(new MembershipCardHandleRelatedGolfClubEvent($membershipCard));
    }
}
