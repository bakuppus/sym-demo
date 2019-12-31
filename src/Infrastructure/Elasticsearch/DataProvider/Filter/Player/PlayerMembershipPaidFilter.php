<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Elasticsearch\DataProvider\Filter\AbstractFilter;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;

class PlayerMembershipPaidFilter extends AbstractFilter
{
    public const PAID = 'paid';
    public const NOT_PAID = 'not_paid';

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
                case self::PAID:
                    $queries[] = $this->getMembershipPaidQuery($golfClubId);
                    break;
                case self::NOT_PAID:
                    $queries[] = $this->getMembershipNotPaidQuery($golfClubId);
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

    public function getMembershipNotPaidQuery(int $golfClubId): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $golfClubQuery = new Nested();
        $golfClubQuery->setPath('related_club.player_membership');
        $golfClubQuery->setQuery(new Match('related_club.player_membership.golf_club_id', $golfClubId));
        $boolQuery->addMust($golfClubQuery);

        $stateBool = new BoolQuery();
        foreach (MembershipCard::STATE_LIST as $state) {
            if (MembershipCard::STATE_PAID === $state) {
                continue;
            }

            $stateQuery = new Nested();
            $stateQuery->setPath('related_club.player_membership');
            $stateQuery->setQuery(new Match('related_club.player_membership.state', $state));
            $stateBool->addShould($stateQuery);
        }
        $boolQuery->addMust($stateBool);

        return $boolQuery;
    }

    public function getMembershipPaidQuery(int $golfClubId): AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $golfClubQuery = new Nested();
        $golfClubQuery->setPath('related_club.player_membership');
        $golfClubQuery->setQuery(new Match('related_club.player_membership.golf_club_id', $golfClubId));
        $boolQuery->addMust($golfClubQuery);

        $stateQuery = new Nested();
        $stateQuery->setPath('related_club.player_membership');
        $stateQuery->setQuery(new Match('related_club.player_membership.state', MembershipCard::STATE_PAID));
        $boolQuery->addMust($stateQuery);

        return $boolQuery;
    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->getProperties($resourceClass) as $property) {
            $description[$property . sprintf('[%s]', self::PAID)] = [
                'property' => $property,
                'type' => 'integer',
                'required' => false,
                'swagger' => [
                    'type' => 'integer',
                    'description' => 'golf_club_id',
                ],
            ];

            $description[$property . sprintf('[%s]', self::NOT_PAID)] = [
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
