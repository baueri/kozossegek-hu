<?php

namespace Framework\Model\Relation;

use Framework\Model\Entity;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait HasMany
{
    public function loadHasManyRelations($instances): void
    {
        $instances = collect($instances);
        $relations = $this->preparedRelations['hasMany'];
        if (!$relations || $instances->isEmpty()) {
            return;
        }

        /* @var $relations Relation[] */
        foreach ($relations as $relation) {
            $relation->applyQueryCallbacks($this->preparedCallbacks[$relation->relationName] ?? []);

            if (in_array($relation->relationName, $this->relationCounts)) {
                $this->fillCounts($relation, $instances);
                return;
            }

            $rows = $relation->buildQuery($instances)->get();

            foreach ($instances as $actualInstance) {
                $actualInstance->relations[$relation->relationName] = $rows->filter(
                    fn ($relationInstance) => $relationInstance->{$relation->foreginKey} == $actualInstance->{$relation->localKey}
                );
            }
        }
    }

    /**
     * @param Relation $relation
     * @param Collection<Entity> $instances
     * @return void
     */
    private function fillCounts(Relation $relation, Collection $instances): void
    {
        $rows = $relation->buildQuery($instances)->countBy($relation->foreginKey);
        foreach ($instances as $actualInstance) {
            $actualInstance->relations_count[$relation->relationName] = $rows[$actualInstance->getId()] ?? 0;
        }
    }

    public function hasMany(string $repositoryClass, ?string $foreingkey = null, ?string $localKey = null): self
    {
        $this->preparedRelations['hasMany'][$this->getRelationName()] = new Relation(
            app($repositoryClass),
            $this->getRelationName(),
            $foreingkey ?? StringHelper::snake(get_class_name(static::getModelClass())) . '_id',
            $localKey
        );

        return $this;
    }
}
