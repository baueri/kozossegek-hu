<?php

namespace Framework\Model\Relation;

use Framework\Database\Builder;
use Framework\Model\Entity;
use Framework\Model\EntityQueryBuilder;
use Framework\Support\Collection;

class Relation
{
    public readonly ?string $foreginKey;

    public readonly ?string $localKey;

    public function __construct(
        public readonly Has $relationType,
        public readonly EntityQueryBuilder|Builder $queryBuilder,
        public readonly string $relationName,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ) {
        $this->localKey = $localKey ?? $queryBuilder::primaryCol();
        $this->foreginKey = $foreignKey ?? $this->relationName . '_id';
    }

    public function buildQuery(Collection|Entity $instances): EntityQueryBuilder|Builder
    {
        return $this->queryBuilder->whereIn($this->foreginKey, collect($instances)->pluck($this->localKey));
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement __call() method.
    }
}
