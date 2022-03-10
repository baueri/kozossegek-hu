<?php

namespace Framework\Model\Relation;

use Framework\Database\Builder;
use Framework\Support\Collection;

trait HasRelations
{
    use HasMany;

    private array $relationCounts = [];

    /**
     * @var \Framework\Support\Collection<\Framework\Model\Relation\Relation>
     */
    protected Collection $preparedRelations;

    protected function loadRelations($instances)
    {
        return tap($instances, fn ($instances) => $this->loadHasManyRelations($instances));
    }

    protected function getRelationName(): string
    {
        $bactrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $bactrace[2]['function'];
    }

    public function with(string $relation, $callback = null): static
    {
        $this->getPreparedRelations()[$relation] = $this->getRelation($relation, $callback);
        return $this;
    }

    public function withCount(string $relation, $callback = null): static
    {
        $this->with($relation, $callback);
        $this->relationCounts[] = $relation;
        return $this;
    }

    public function whereHas(string $relationName, $callback): static
    {
        $relation = $this->getRelation($relationName, $callback);
        $relation->entityQueryBuilder->whereRaw("{$relation->entityQueryBuilder->getTable()}.{$relation->foreginKey}={$this->getTable()}.{$relation->localKey}");
        $this->whereExists($relation->entityQueryBuilder);
        return $this;
    }

    public function withWhereHas(string $relation, $callback = null): static
    {
        $this->with($relation, $callback);
        return $this->whereHas($relation, $callback);
    }

    public function withCountWhereHas(string $relation, $callback = null): static
    {
        $this->withCount($relation, $callback);
        return $this->whereHas($relation, $callback);
    }

    public function getRelation(string $relation, $callback = null): Relation
    {
        /** @var \Framework\Model\Relation\Relation $rel */
        $rel = $this->{$relation}();
        $callback($rel->entityQueryBuilder);
        return $rel;
    }

    protected function getPreparedRelations(): Collection
    {
        return $this->preparedRelations ??= collect();
    }
}
