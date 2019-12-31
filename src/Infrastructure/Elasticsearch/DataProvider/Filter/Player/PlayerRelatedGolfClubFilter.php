<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Carbon\Carbon;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;
use Elastica\Query\Range;

class PlayerRelatedGolfClubFilter extends AbstractFilter
{
    public function apply(
        Query $query,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): Query {
        foreach ($this->getProperties($resourceClass) as $property) {
            if (false === isset($context['filters'][$property])) {
                continue;
            }
            $golfClubId = (integer) $context['filters'][$property];

            $this->addQuery($query, $golfClubId);
        }

        return $query;
    }

    public function addQuery(Query $query, int $golfClubId): void
    {
        $boolQuery = $this->getBoolQuery($golfClubId);
        $this->stackMust($query, $boolQuery);
    }

    public function getBoolQuery(int $golfClubId): BoolQuery
    {
        $boolQuery = new BoolQuery();

        $this->addLastPlayedBookingQuery($boolQuery, $golfClubId);
        $this->addPlayerMembershipQuery($boolQuery, $golfClubId);
        $this->addPlayerMembershipToAssignQuery($boolQuery, $golfClubId);
        $this->addPlayRightQuery($boolQuery, $golfClubId);

        return $boolQuery;
    }

    protected function addPlayRightQuery(BoolQuery $boolQuery, int $golfClubId): void
    {
        $nested = new Nested();
        $nested->setPath('related_club.play_right');
        $nested->setQuery(new Match('related_club.play_right.golf_club_id', $golfClubId));

        $boolQuery->addShould($nested);
    }

    protected function addPlayerMembershipToAssignQuery(BoolQuery $boolQuery, int $golfClubId): void
    {
        $nested = new Nested();
        $nested->setPath('related_club.player_membership_to_assign');
        $nested->setQuery(new Match('related_club.player_membership_to_assign.golf_club_id', $golfClubId));

        $boolQuery->addShould($nested);
    }

    protected function addPlayerMembershipQuery(BoolQuery $boolQuery, int $golfClubId): void
    {
        $nested = new Nested();
        $nested->setPath('related_club.player_membership');

        $playerMembershipBool = new BoolQuery();
        $playerMembershipBool->addMust(new Range('related_club.player_membership.expires_at', [
            'gte' => Carbon::now()->subYears(2),
        ]));
        $playerMembershipBool->addMust(new Match('related_club.player_membership.golf_club_id', $golfClubId));

        $nested->setQuery($playerMembershipBool);

        $boolQuery->addShould($nested);
    }

    protected function addLastPlayedBookingQuery(BoolQuery $boolQuery, int $golfClubId): void
    {
        $nested = new Nested();
        $nested->setPath('related_club.last_played_bookings');

        $lastPlayedBookingsBool = new BoolQuery();
        $lastPlayedBookingsBool->addMust(new Range('related_club.last_played_bookings.start_time', [
            'gte' => Carbon::now()->subYears(2),
        ]));
        $lastPlayedBookingsBool->addMust(
            new Match('related_club.last_played_bookings.golf_club_id', $golfClubId)
        );

        $nested->setQuery($lastPlayedBookingsBool);

        $boolQuery->addShould($nested);
    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                ],
            ];
        }

        return $description;
    }
}
