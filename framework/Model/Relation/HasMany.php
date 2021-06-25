<?php

namespace Framework\Model\Relation;

trait HasMany
{
    public function loadHasManyRelations($instances)
    {
        if ($relations = $this->preparedRelations['hasMany'] ?? null) {
            /* @var $relations \Framework\Model\Relation\Relation[] */
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
                            $instances->pluck($localKey)->toArray()
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

    public function hasMany(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null)
    {
        $this->preparedRelations['hasMany'][] = new Relation(
            app($repositoryClass),
            $this->getRelationName(),
            $foreingkey,
            $localKey
        );

        return $this;
    }
}
