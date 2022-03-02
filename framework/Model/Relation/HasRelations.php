<?php

namespace Framework\Model\Relation;

trait HasRelations
{
    use HasMany;

    private array $relationCounts = [];
    protected array $preparedRelations = ['hasMany' => [], 'belongsTo' => []];
    protected array $preparedCallbacks = [];

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
        $this->{$relation}();

        if ($callback) {
            $this->preparedCallbacks[$relation][] = $callback;
        }

        return $this;
    }

    public function withCount(string $relation, $callback = null): static
    {
        $this->with($relation, $callback);
        $this->relationCounts[] = $relation;
        return $this;
    }
}
