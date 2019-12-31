<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\TeeTimeBooking;

use App\Domain\Player\Player;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Doctrine\Listener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeeTimeBookingHandleRelatedGolfClubEventSubscriber implements EventSubscriberInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            TeeTimeBookingHandleRelatedGolfClubEvent::class => 'handleRelatedGolfClub',
        ];
    }

    public function handleRelatedGolfClub(TeeTimeBookingHandleRelatedGolfClubEvent $event): void
    {
        $booking = $event->getBooking();
        if (null === $booking) {
            return;
        }
        $player = $booking->getOwner();
        if (null === $player || false === $this->shouldUpdatePlayer($player)) {
            return;
        }

        foreach ($this->entityManager->getEventManager()->getListeners(Events::postPersist) as $listener) {
            if ($listener instanceof Listener) {
                $listener->postUpdate(new LifecycleEventArgs($player, $this->entityManager));
                $listener->postFlush();
            }
        }
    }

    public function shouldUpdatePlayer(Player $player): bool
    {
        return null !== $this->entityManager->getUnitOfWork()->getSingleIdentifierValue($player);
    }
}
