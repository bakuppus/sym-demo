<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\PlayerMembershipToAssign;

use App\Domain\Membership\PlayerMembershipToAssign;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PlayerMembershipToAssignDoctrineEventSubscriber implements EventSubscriber
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
        $playerMembershipToAssign = $args->getObject();
        if (!$playerMembershipToAssign instanceof PlayerMembershipToAssign) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PlayerMembershipToAssignHandleRelatedGolfClubEvent($playerMembershipToAssign)
        );
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $playerMembershipToAssign = $args->getObject();
        if (!$playerMembershipToAssign instanceof PlayerMembershipToAssign) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PlayerMembershipToAssignHandleRelatedGolfClubEvent($playerMembershipToAssign)
        );
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $playerMembershipToAssign = $args->getObject();
        if (!$playerMembershipToAssign instanceof PlayerMembershipToAssign) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PlayerMembershipToAssignHandleRelatedGolfClubEvent($playerMembershipToAssign)
        );
    }

    public function postSoftDelete(LifecycleEventArgs $args): void
    {
        $playerMembershipToAssign = $args->getObject();
        if (!$playerMembershipToAssign instanceof PlayerMembershipToAssign) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PlayerMembershipToAssignHandleRelatedGolfClubEvent($playerMembershipToAssign)
        );
    }
}
