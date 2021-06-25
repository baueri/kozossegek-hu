<?php

namespace Framework\Model\Relation;

use Framework\Model\EntityQueryBuilder;

class Relation
{
    private EntityQueryBuilder $builder;

    private string $relationName;

    private ?string $foreginKey;

    private ?string $localKey;

    public function __construct(
        EntityQueryBuilder $builder,
        string $relationName,
        ?string $foreginKey = null,
        ?string $localKey = null
    ) {
        $this->builder = $builder;
        $this->relationName = $relationName;
        $this->foreginKey = $foreginKey;
        $this->localKey = $localKey;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function relationName()
    {
        return $this->relationName;
    }

    public function getForeignKey()
    {
        return $this->foreginKey;
    }

    public function getLocalKey()
    {
        return $this->localKey;
    }
}
