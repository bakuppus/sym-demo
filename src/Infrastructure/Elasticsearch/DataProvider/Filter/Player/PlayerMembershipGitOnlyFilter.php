<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;

class PlayerMembershipGitOnlyFilter extends AbstractFilter
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
            $golfClubId = $context['filters'][$property];
            if (null === $golfClubId) {
                return $query;
            }
            $golfClubId = (int) $golfClubId;

            $this->addQuery($query, $golfClubId);
        }

        return $query;
    }

    public function addQuery(Query $query, int $golfClubId): void
    {
        $boolQuery = new BoolQuery();

        $toAssign = new Nested();
        $toAssign->setPath('related_club.player_membership_to_assign');
        $toAssign->setQuery(new Match('related_club.player_membership_to_assign.golf_club_id', $golfClubId));
        $boolQuery->addMust($toAssign);

        $playerMembership = new Nested();
        $playerMembership->setPath('related_club.player_membership');
        $playerMembership->setQuery(new Match('related_club.player_membership.golf_club_id', $golfClubId));
        $boolQuery->addMustNot($playerMembership);

        $this->stackMust($query, $boolQuery);
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
                    'description' => 'golf_club_id',
                ],
            ];
        }

        return $description;
    }
}
