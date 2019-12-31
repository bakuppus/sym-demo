<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\TeeTimeBooking;

use App\Domain\Booking\TeeTimeBooking;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TeeTimeBookingDoctrineEventSubscriber implements EventSubscriber
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
        $booking = $args->getObject();
        if (!$booking instanceof TeeTimeBooking) {
            return;
        }

        $this->eventDispatcher->dispatch(new TeeTimeBookingHandleRelatedGolfClubEvent($booking));
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $booking = $args->getObject();
        if (!$booking instanceof TeeTimeBooking) {
            return;
        }

        $this->eventDispatcher->dispatch(new TeeTimeBookingHandleRelatedGolfClubEvent($booking));
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $booking = $args->getObject();
        if (!$booking instanceof TeeTimeBooking) {
            return;
        }

        $this->eventDispatcher->dispatch(new TeeTimeBookingHandleRelatedGolfClubEvent($booking));
    }

    public function postSoftDelete(LifecycleEventArgs $args): void
    {
        $booking = $args->getObject();
        if (!$booking instanceof TeeTimeBooking) {
            return;
        }

        $this->eventDispatcher->dispatch(new TeeTimeBookingHandleRelatedGolfClubEvent($booking));
    }
}
