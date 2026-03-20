<?php

declare(strict_types=1);

namespace Framework\Model\Relation;

use Framework\Database\Builder;
use Framework\Model\Entity;
use Framework\Model\EntityQueryBuilder;
use Framework\Support\Collection;

readonly class Relation
{
    public ?string $foreignKey;

    public ?string $localKey;

    public function __construct(
        public Has $relationType,
        public EntityQueryBuilder|Builder $queryBuilder,
        public string $relationName,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ) {
        $this->localKey = $localKey ?? $queryBuilder->primaryCol();
        $this->foreignKey = $foreignKey ?? $this->relationName . '_id';
    }

    public function buildQuery(Collection|Entity $instances): EntityQueryBuilder|Builder
    {
        return $this->queryBuilder->whereIn($this->foreignKey, collect($instances)->pluck($this->localKey)->unique()->filter());
    }
}
