<?php

namespace Framework\Model\Relation;

use Framework\Support\Collection;

/**
 * @deprecated do not use
 */
trait BelongsTo
{
    public function belongsTo(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null): self
    {
//        $this->preparedRelations['belongsTo'][$this->getRelationName()]['relation'] = new Relation(
//            app($repositoryClass),
//            $this->getRelationName(),
//            $foreingkey,
//            $localKey
//        );
//
//        return $this;
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
