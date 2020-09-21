<?php


namespace Framework\Database\Repository;


use Framework\Support\Collection;

class ModelCollection extends Collection
{
    protected $loadedRelations;

    public function load($relations)
    {
        $this->loadedRelations = $relations;
    }

    public function getRelation($name)
    {
        return $this->loadedRelations[$name];
    }
}