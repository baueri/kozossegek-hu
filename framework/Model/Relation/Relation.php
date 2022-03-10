<?php

namespace Framework\Model\Relation;

use Framework\Model\EntityQueryBuilder;
use Framework\Support\Collection;

class Relation
{
    public readonly ?string $foreginKey;

    public readonly ?string $localKey;

    public function __construct(
        public readonly RelationType $relationType,
        public readonly EntityQueryBuilder $entityQueryBuilder,
        public readonly string $relationName,
        ?string $foreginKey = null,
        ?string $localKey = null,
    ) {
        $this->localKey = $localKey ?? $entityQueryBuilder::primaryCol();
        $this->foreginKey = $foreginKey ?? $this->relationName . '_id';
    }

    public function buildQuery(Collection $instances): EntityQueryBuilder
    {
        return $this->entityQueryBuilder->whereIn($this->foreginKey, $instances->map->{$this->localKey});
    }
}
