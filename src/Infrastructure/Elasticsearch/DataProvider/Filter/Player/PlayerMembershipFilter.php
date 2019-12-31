<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;

class PlayerMembershipFilter extends AbstractFilter
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
            $filters = $context['filters'][$property];

            if (false === isset($filters['golf_club_id'])) {
                return $query;
            }

            $golfClubId = (int) $filters['golf_club_id'];
            $memberships = [];
            foreach ($filters as $key => $row) {
                if ('golf_club_id' === $key) {
                    continue;
                }

                $memberships[] = (int) $row;
            }

            $this->addQuery($query, $golfClubId, $memberships);
        }

        return $query;
    }

    public function addQuery(Query $query, int $golfClubId, array $memberships): void
    {
        if (0 === count($memberships)) {
            return;
        }

        $boolQuery = new BoolQuery();

        foreach ($memberships as $membershipId) {
            $nestedBool = new BoolQuery();

            $playerMembership = new Nested();
            $playerMembership->setPath('related_club.player_membership');

            $playerMembershipBool = new BoolQuery();
            $playerMembershipBool->addMust(new Match('related_club.player_membership.golf_club_id', $golfClubId));

            $playerMembership->setQuery($playerMembershipBool);
            $nestedBool->addMust($playerMembership);

            $membershipBool = new BoolQuery();
            $membershipQuery = new Nested();
            $membershipQuery->setPath('related_club.player_membership.membership');
            $membershipQuery->setQuery(new Match('related_club.player_membership.membership.id', $membershipId));
            $membershipBool->addMust($membershipQuery);
            $nestedBool->addMust($membershipBool);

            $boolQuery->addShould($nestedBool);
        }

        $this->stackMust($query, $boolQuery);
    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . sprintf('[%s]', 'golf_club_id')] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                    'description' => 'golf club id',
                ],
            ];

            $description[$property . '[]'] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                    'description' => 'membership id (array)',
                ],
            ];
        }

        return $description;
    }
}
