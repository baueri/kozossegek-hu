<?php

namespace Framework\Model\Relation;

use Framework\Support\StringHelper;

/**
 * @deprecated do not use
 */
trait BelongsTo
{
    public function belongsTo(string $repositoryClass, ?string $localKey = null, ?string $foreignKey = null)
    {
//        return new Relation(
//            relationType: Has::many,
//            queryBuilder: app($repositoryClass),
//            relationName: $this->getRelationName(),
//            foreignKey: $foreignkey ?? StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
//            localKey: $localKey
//        );
    }

    protected function loadBelongsToRelations($instances): void
    {
//        /** @var Collection $instances */
//        if ($relations = $this->preparedRelations['belongsTo'] ?? null) {
//            /* @var $relations Relation[] */
//            foreach ($relations as $relation) {
//                $primaryCol = $relation->getBuilder()->primaryCol();
//                $relationName = $relation->relationName();
//                $foreignKey = $relation->getForeignKey() ?? $primaryCol;
//                $localKey = $relation->getLocalKey() ?? $relationName . '_id';
//
//                $belongsToRelationRows = collect();
//
//                if ($instances->isNotEmpty()) {
//                    $belongsToRelationRows = $relation->getBuilder()
//                        ->whereIn(
//                            $foreignKey,
//                            $instances->pluck($localKey)->all()
//                        )->get();
//                }
//
//                foreach ($instances as $instance) {
//                    $instance->relations[$relationName] = $belongsToRelationRows->filter(
//                        fn ($model) => $model->{$foreignKey} == $instance->{$localKey}
//                    )->first();
//                }
//            }
//        }
    }
}
