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
}