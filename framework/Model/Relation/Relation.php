<?php

namespace Framework\Model\Relation;

use Framework\Model\EntityQueryBuilder;
use Framework\Support\Collection;

class Relation
{
    public readonly ?string $foreginKey;

    public readonly ?string $localKey;

    public function __construct(
        private readonly EntityQueryBuilder $builder,
        public readonly string $relationName,
        ?string $foreginKey = null,
        ?string $localKey = null
    ) {
        $this->localKey = $localKey ?? $builder::primaryCol();
        $this->foreginKey = $foreginKey ?? $this->relationName . '_id';
    }

    public function applyQueryCallbacks(array $callbacks)
    {
        array_walk($callbacks, fn ($callback) => $callback($this->builder));
    }

    public function buildQuery(Collection $instances): EntityQueryBuilder
    {
        return $this->builder->whereIn($this->foreginKey, $instances->map->{$this->localKey});
    }
}
