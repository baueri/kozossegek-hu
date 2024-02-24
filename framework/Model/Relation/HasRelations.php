<?php

namespace Framework\Model\Relation;

use Framework\Support\Collection;

trait HasRelations
{
    use HasMany;

    private array $relationCounts = [];

    /**
     * @var Collection<Relation>
     */
    protected Collection $preparedRelations;

    protected function loadRelations($instances)
    {
        return tap($instances, fn ($instances) => $this->loadHasRelations($instances));
    }

    protected function getRelationName(): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $backtrace[2]['function'];
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

    public function whereHas(string $relationName, $callback = null): static
    {
        $relation = $this->getRelation($relationName, $callback);
        $relation->queryBuilder->whereRaw("{$relation->queryBuilder->getTable()}.{$relation->foreignKey}={$this->getTable()}.{$relation->localKey}");
        $this->whereExists($relation->queryBuilder);
        return $this;
    }

    public function getRelation(string $relation, $callback = null): Relation
    {
        /** @var Relation $rel */
        $rel = $this->{$relation}();
        if ($callback) {
            $callback($rel->queryBuilder);
        }
        return $rel;
    }

    /**
     * @phpstan-return Collection<Relation>
     */
    protected function getPreparedRelations(): Collection
    {
        return $this->preparedRelations ??= collect();
    }
}
