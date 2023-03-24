<?php

namespace App\Repositories;

use App\Enums\CityStatOrderColumn;
use App\Models\CityStat;
use Framework\Database\Builder;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

final class CityStatistics extends EntityQueryBuilder
{
    public const INTERACTION_MIN_WEIGHT = 10;

    public const TABLE = 'stat_city';

    public static function getModelClass(): string
    {
        return CityStat::class;
    }

    public function selectSums(): self
    {
        return $this->select([
            'city',
            'sum(search_count) as search_count',
            'sum(opened_groups_count) as opened_groups_count',
            'sum(contacted_groups_count) as contacted_groups_count'
        ])
        ->groupBy('city');
    }

    public function orderBySum(CityStatOrderColumn $column): self
    {
        return $this->orderBy($column->name, 'desc');
    }

    public function onPeriod(?string $period): self
    {
        if (!$period) {
            return $this;
        }

        return match ($period) {
            'week' => $this->whereRaw('WEEK(date, 1) = WEEK(NOW(), 1) AND YEAR(date) = YEAR(NOW())'),
            'month' => $this->whereRaw('MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())'),
            'today' => $this->whereRaw('date = DATE(NOW())'),
            'yesterday' => $this->whereRaw('date = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))')
        };
    }
}