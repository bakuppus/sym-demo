<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\PlayRight;

use App\Domain\Player\PlayRight;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PlayRightDoctrineEventSubscriber implements EventSubscriber
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
        $playRight = $args->getObject();
        if (!$playRight instanceof PlayRight) {
            return;
        }

        $this->eventDispatcher->dispatch(new PlayRightHandleRelatedGolfClubEvent($playRight));
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $playRight = $args->getObject();
        if (!$playRight instanceof PlayRight) {
            return;
        }

        $this->eventDispatcher->dispatch(new PlayRightHandleRelatedGolfClubEvent($playRight));
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $playRight = $args->getObject();
        if (!$playRight instanceof PlayRight) {
            return;
        }

        $this->eventDispatcher->dispatch(new PlayRightHandleRelatedGolfClubEvent($playRight));
    }

    public function postSoftDelete(LifecycleEventArgs $args): void
    {
        $playRight = $args->getObject();
        if (!$playRight instanceof PlayRight) {
            return;
        }

        $this->eventDispatcher->dispatch(new PlayRightHandleRelatedGolfClubEvent($playRight));
    }
}
