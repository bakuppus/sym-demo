<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\DataProvider\Filter\Player;

use Elastica\Query;

class PlayerExternalFilter extends PlayerRelatedGolfClubFilter
{
    public function addQuery(Query $query, int $golfClubId): void
    {
        $boolQuery = $this->getBoolQuery($golfClubId);
        $this->stackMustNot($query, $boolQuery);
    }
}
