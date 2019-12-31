<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;

class PlayerTypeFilter extends AbstractFilter
{
    public const MEMBER_FILTER = 'members';
    public const GUEST_FILTER = 'guests';

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
            $filters = $this->normalizeFilters($context['filters'][$property]);
            $this->addQuery($query, $filters);
        }

        return $query;
    }

    public function normalizeFilters(array $filters): array
    {
        return array_map(function ($filter): int {
            return (int) $filter;
        }, $filters);
    }

    public function addQuery(Query $query, array $filters): void
    {
        $queries = [];
        foreach ($filters as $filter => $golfClubId) {
            switch ($filter) {
                case self::MEMBER_FILTER:
                    $queries[] = $this->getMemberQuery($golfClubId);
                    break;
                case self::GUEST_FILTER:
                    $queries[] = $this->getGuestFilter($golfClubId);
                    break;
            }
        }

        if (0 === count($queries)) {
            return;
        }

        $boolQuery = new BoolQuery();
        foreach ($queries as $filterQuery) {
            $boolQuery->addShould($filterQuery);
        }

        $this->stackMust($query, $boolQuery);
    }

    public function getMemberQuery(int $golfClubId): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $playerMembership = new Nested();
        $playerMembership->setPath('related_club.player_membership');

        $playerMembershipBool = new BoolQuery();
        $playerMembershipBool->addMust(
            new Match('related_club.player_membership.golf_club_id', $golfClubId)
        );
        $playerMembership->setQuery($playerMembershipBool);

        $boolQuery->addShould($playerMembership);

        $toAssign = new Nested();
        $toAssign->setPath('related_club.player_membership_to_assign');
        $toAssign->setQuery(new Match('related_club.player_membership_to_assign.golf_club_id', $golfClubId));
        $boolQuery->addShould($toAssign);

        return $boolQuery;
    }

    public function getGuestFilter(int $golfClubId): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $playedBooking = new Nested();
        $playedBooking->setPath('related_club.last_played_bookings');
        $playedBooking->setQuery(
            new Query\Match('related_club.last_played_bookings.golf_club_id', $golfClubId)
        );
        $boolQuery->addMust($playedBooking);

        $boolQuery->addMustNot($this->getMemberQuery($golfClubId));

        return $boolQuery;
    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . sprintf('[%s]', self::MEMBER_FILTER)] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                    'description' => 'golf club id',
                ],
            ];

            $description[$property . sprintf('[%s]', self::GUEST_FILTER)] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                    'description' => 'golf club id',
                ],
            ];
        }

        return $description;
    }
}
