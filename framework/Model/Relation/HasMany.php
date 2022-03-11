<?php

namespace Framework\Model\Relation;

use Framework\Model\Entity;
use Framework\Support\Arr;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait HasMany
{
    public function loadHasManyRelations($instances): void
    {
        $instances = collect($instances);
        $relations = $this->getPreparedRelations()->filter(fn (Relation $relation) => $relation->relationType === RelationType::HasMany);
        if ($relations->isEmpty() || $instances->isEmpty()) {
            return;
        }

        /* @var $relations Relation[] */
        foreach ($relations as $relation) {
            if (in_array($relation->relationName, $this->relationCounts)) {
                $this->fillCounts($relation, $instances);
                return;
            }
            $rows = collect($relation->buildQuery($instances)->get());

            foreach ($instances as $actualInstance) {
                $actualInstance->relations[$relation->relationName] = $rows->filter(
                    fn ($relationInstance) => Arr::get($relationInstance, $relation->foreginKey) == Arr::get($actualInstance, $relation->localKey)
                );
            }
        }
    }

    /**
     * @param Relation $relation
     * @param Collection<Entity> $instances
     */
    private function fillCounts(Relation $relation, Collection $instances): void
    {
        $rows = $relation->buildQuery($instances)->countBy($relation->foreginKey);
        foreach ($instances as $actualInstance) {
            $actualInstance->relations_count[$relation->relationName] = $rows[$actualInstance->getId()] ?? 0;
        }
    }

    public function hasMany(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null): Relation
    {
        return new Relation(
            relationType: RelationType::HasMany,
            queryBuilder: app($repositoryClass),
            relationName: $this->getRelationName(),
            foreginKey: $foreingkey ?? StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
            localKey: $localKey
        );
    }
}
