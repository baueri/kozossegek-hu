<?php

namespace App\QueryBuilders;

use App\Models\City;
use App\Repositories\CityStatistics;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

class Cities extends EntityQueryBuilder
{
    public static function getModelClass(): string
    {
        return City::class;
    }

    public function statistics(): Relation
    {
        return $this->has(Has::one, CityStatistics::class, 'city', 'name');
    }

    public function groups(): Relation
    {
        return $this->has(Has::many, GroupViews::class, 'city', 'name');
    }

    public function search(?string $term): static
    {
        if (!$term) {
            return $this;
        }

        return $this->where('name', 'like', "%{$term}%")
            ->limit(10)
            ->orderBy('name', 'desc');
    }
}