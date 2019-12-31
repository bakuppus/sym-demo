<?php

declare(strict_types=1);

namespace App\Application\Event\Doctrine\MembershipCard;

use App\Domain\Player\Player;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Doctrine\Listener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MembershipCardHandleRelatedGolfClubEventSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            MembershipCardHandleRelatedGolfClubEvent::class => 'handleRelatedGolfClub',
        ];
    }

    public function handleRelatedGolfClub(MembershipCardHandleRelatedGolfClubEvent $event): void
    {
        $membershipCard = $event->getMembershipCard();
        if (null === $membershipCard) {
            return;
        }
        $player = $membershipCard->getPlayer();
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
