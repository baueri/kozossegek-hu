<?php

namespace Framework\Model\Relation;

use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait HasMany
{
    public function loadHasManyRelations($instances): void
    {
        /** @var Collection $instances */
        if ($relations = $this->preparedRelations['hasMany'] ?? null) {
            /* @var $relations Relation[] */
            foreach ($relations as $relation) {
                $primaryCol = $relation->getBuilder()->primaryCol();
                $relationName = $relation->relationName();
                $foreignKey = $relation->getForeignKey() ?? $relationName . '_id';

                $localKey = $relation->getLocalKey() ?? $primaryCol;

                $rows = collect();

                if ($instances->isNotEmpty()) {
                    $rows = $relation->getBuilder()
                        ->whereIn(
                            $foreignKey,
                            $instances->pluck($localKey)->all()
                        )->get();
                }

                foreach ($instances as $actualInstance) {
                    $actualInstance->relations[$relationName] = $rows->filter(
                        fn ($relationInstance) => $relationInstance->{$foreignKey} == $actualInstance->{$localKey}
                    );
                }
            }
        }
    }

    public function hasMany(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null): self
    {
        $this->preparedRelations['hasMany'][] = new Relation(
            app($repositoryClass),
            $this->getRelationName(),
            $foreingkey ?? StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
            $localKey
        );

        return $this;
    }
}
