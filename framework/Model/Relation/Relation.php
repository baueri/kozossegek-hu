<?php

namespace Framework\Model\Relation;

use Framework\Database\Builder;
use Framework\Model\EntityQueryBuilder;
use Framework\Support\Collection;

class Relation
{
    public readonly ?string $foreginKey;

    public readonly ?string $localKey;

    public function __construct(
        public readonly RelationType $relationType,
        public readonly EntityQueryBuilder|Builder $queryBuilder,
        public readonly string $relationName,
        ?string $foreginKey = null,
        ?string $localKey = null,
    ) {
        $this->localKey = $localKey ?? $queryBuilder::primaryCol();
        $this->foreginKey = $foreginKey ?? $this->relationName . '_id';
    }

    public function buildQuery(Collection $instances): EntityQueryBuilder|Builder
    {
        return $this->queryBuilder->whereIn($this->foreginKey, $instances->pluck($this->localKey));
    }
}
