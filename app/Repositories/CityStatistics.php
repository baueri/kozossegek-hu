<?php

namespace App\Repositories;

use App\Enums\CityStatOrderColumn;
use Framework\Database\Builder;
use Framework\Database\Database;

final class CityStatistics extends Builder
{
    public function __construct(Database $db)
    {
        parent::__construct($db);
        $this->from('stat_city');
    }

    public function selectSums(): self
    {
        return $this->select('city, sum(search_count) as search_count, sum(opened_groups_count) as opened_groups_count, sum(contacted_groups_count) as contacted_groups_count')
            ->groupBy('city');
    }

    public function orderBySum(CityStatOrderColumn $column): self
    {
        return $this->orderBy($column->name, 'desc');
    }

    public function onPeriod(?string $period)
    {
        if (!$period) {
            return $this;
        }

        switch ($period) {
            case 'week':
                $this->whereRaw('WEEK(date, 1) = WEEK(NOW(), 1) AND YEAR(date) = YEAR(NOW())');
            break;
            case 'month':
                $this->whereRaw('MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())');
            break;
            case 'yesterday':
                $this->whereRaw('date = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))');
        }
        return $this;
    }
}