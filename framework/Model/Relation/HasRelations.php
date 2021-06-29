<?php

namespace Framework\Model\Relation;

trait HasRelations
{
    use BelongsTo;
    use HasMany;

    protected function loadRelations($instances)
    {
        $this->loadBelongsToRelations($instances);
        $this->loadHasManyRelations($instances);
    }

    protected function getRelationName(): string
    {
        $bactrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $bactrace[2]['function'];
    }

    public function with(string $relation)
    {
        $this->{$relation}();

        return $this;
    }

    public function hasRelations(): bool
    {
        return !empty($this->preparedRelations);
    }
}
