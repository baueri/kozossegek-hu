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
        $relations = $this->getPreparedRelations();

        if ($relations->isEmpty() || $instances->isEmpty()) {
            return;
        }

        /* @var $relations Relation[] */
        foreach ($relations as $relation) {
            if (in_array($relation->relationName, $this->relationCounts)) {
                $this->fillCounts($relation, $instances);
                continue;
            }
            $rows = collect($relation->buildQuery($instances)->get());

            foreach ($instances as $actualInstance) {
                $actualInstanceValue = Arr::get($actualInstance, $relation->localKey);
                $isSame = fn($relationInstance) => Arr::get($relationInstance, $relation->foreginKey) == $actualInstanceValue;
                if ($relation->relationType == Has::many) {
                    $actualInstance->relations[$relation->relationName] = $rows->filter($isSame)->values();
                } else {
                    $actualInstance->relations[$relation->relationName] = $rows->first($isSame);
                }
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
            $actualInstance->relations_count[$relation->relationName] = $rows[Arr::getItemValue($actualInstance, $relation->localKey)] ?? 0;
        }
    }

    public function hasMany(string $repositoryClass, ?string $foreignKey = null, ?string $localKey = null): Relation
    {
        return $this->has(Has::many, $repositoryClass, $foreignKey, $localKey);
    }

    public function hasOne(string $repositoryClass, ?string $foreignKey = null, ?string $localKey = null): Relation
    {
        return $this->has(Has::one, $repositoryClass, $foreignKey, $localKey);
    }

    public function has(Has $relationType, string $repositoryClass, ?string $foreignKey = null, ?string $localKey = null): Relation
    {
        return new Relation(
            relationType: $relationType,
            queryBuilder: app($repositoryClass),
            relationName: $this->getRelationName(),
            foreignKey: $foreignKey ?? StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
            localKey: $localKey
        );
    }
}
