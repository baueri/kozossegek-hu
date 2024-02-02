<?php

namespace Framework\Model\Relation;

use Framework\Database\Builder;
use Framework\Model\Entity;
use Framework\Model\EntityQueryBuilder;
use Framework\Support\Arr;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait HasMany
{
    public function loadHasRelations($instances): void
    {
        $instances = collect($instances);
        $relations = $this->getPreparedRelations()->filter(fn (Relation $relation) => $relation->relationType instanceof Has);

        if ($relations->isEmpty() || $instances->isEmpty()) {
            return;
        }

        foreach ($relations as $relation) {
            $this->fillRelations($instances, $relation, in_array($relation->relationName, $this->relationCounts));
        }
    }

    public function fillRelations(Collection|Entity $instances, Relation $relation, bool $loadCount = false): void
    {
        $instances = collect($instances);
        if ($loadCount) {
            $this->fillCounts($relation, $instances);
            return;
        }
        $rows = collect($relation->buildQuery($instances)->get());
        foreach ($instances as $actualInstance) {
            $actualInstanceValue = Arr::get($actualInstance, $relation->localKey);
            $isSame = fn($relationInstance) => Arr::get($relationInstance, $relation->foreignKey) == $actualInstanceValue;
            if ($relation->relationType == Has::many) {
                $actualInstance->relations[$relation->relationName] = $rows->filter($isSame)->values();
            } else {
                $actualInstance->relations[$relation->relationName] = $rows->first($isSame);
            }
        }
    }

    /**
     * @param Relation $relation
     * @param Collection<Entity> $instances
     */
    private function fillCounts(Relation $relation, Collection $instances): void
    {
        $rows = $relation->buildQuery($instances)->countBy($relation->foreignKey);
        foreach ($instances as $actualInstance) {
            $actualInstance->relations_count[$relation->relationName] = $rows[Arr::getItemValue($actualInstance, $relation->localKey)] ?? 0;
        }
    }

    public function has(Has $relationType, string|EntityQueryBuilder|Builder $repositoryClass, ?string $foreignKey = null, ?string $localKey = null): Relation
    {
        return new Relation(
            relationType: $relationType,
            queryBuilder: is_string($repositoryClass) ? app($repositoryClass) : $repositoryClass,
            relationName: $this->getRelationName(),
            foreignKey: $foreignKey ?: StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
            localKey: $localKey
        );
    }
}
