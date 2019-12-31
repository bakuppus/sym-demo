<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\Event\Player;

use App\Domain\Player\Player;
use App\Infrastructure\Elasticsearch\Event\Shared\ElasticHydrationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PlayerElasticHydrationEvent implements EventSubscriberInterface
{
    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            ElasticHydrationEvent::class => 'onElasticHydration',
        ];
    }

    public function onElasticHydration(ElasticHydrationEvent $event)
    {
        $result = $event->getHybridResult();
        $player = $result->getTransformed();
        if (false === $player instanceof Player) {
            return;
        }

        /** @var Player $player */
        $golfClubId = $this->requestStack->getCurrentRequest()->get('golfClub');
        if (null !== $golfClubId) {
            $golfClubId = (int) $golfClubId;
            $player->isMembershipPaid($golfClubId);
            $player->isGitMember($golfClubId);
            $player->hasPlayRight($golfClubId);
            $player->shownMemberships($golfClubId);
            $player->shownOneMembership($golfClubId);
            $player->showPlayerMembershipToAssign($golfClubId);
        }
    }
}
