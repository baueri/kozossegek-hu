<?php

namespace Framework\Model\Relation;

use Framework\Model\EntityQueryBuilder;
use Framework\Support\Arr;

trait BelongsTo
{
    protected array $preparedRelations = [];

    public function belongsTo(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null)
    {
        $this->preparedRelations['belongsTo'][] = new Relation(
            app($repositoryClass),
            $this->getRelationName(),
            $foreingkey,
            $localKey
        );

        return $this;
    }

    protected function loadBelongsToRelations($instances): void
    {
        if ($relations = $this->preparedRelations['belongsTo'] ?? null) {
            /* @var $relations \Framework\Model\Relation\Relation[] */
            foreach ($relations as $relation) {
                $primaryCol = $relation->getBuilder()->primaryCol();
                $relationName = $relation->relationName();
                $foreignKey = $relation->getForeignKey() ?? $primaryCol;
                $localKey = $relation->getLocalKey() ?? $relationName . '_id';

                $belongsToRelationRows = collect();

                if ($instances->isNotEmpty()) {
                    $belongsToRelationRows = $relation->getBuilder()
                        ->whereIn(
                            $foreignKey,
                            $instances->pluck($localKey)->toArray()
                        )->get();
                }

                foreach ($instances as $instance) {
                    $instance->relations[$relationName] = $belongsToRelationRows->filter(
                        fn ($model) => $model->{$foreignKey} == $instance->{$localKey}
                    )->first();
                }
            }
        }
    }
}
