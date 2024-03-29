<?php

namespace App\Services\Statistics\Aggregators;

use App\Enums\EventType;
use App\Repositories\CityStatistics;
use Carbon\Carbon;

class CityStatAggregator extends StatAggregator
{
    private array $aggregated = [];

    public function add(array $row): void
    {
        $city = $this->getCity($row);
        if (
            !in_array(EventType::tryFrom($row['type']), [EventType::search, EventType::group_profile_opened, EventType::group_contact])
            || !$city
        ) {
            return;
        }

        $key = $this->compositeKey($row);
        if (!isset($this->aggregated[$key])) {
            $this->aggregated[$key] = [
                'city' => $city,
                'search_count' => 0,
                'opened_groups_count' => 0,
                'contacted_groups_count' => 0,
                'date' => Carbon::parse($row['created_at'])->toDateString()
            ];
        }
        if ($row['type'] === EventType::search->name) {
            $this->aggregated[$key]['search_count']++;
        }

        if ($row['type'] === EventType::group_profile_opened->name) {
            $this->aggregated[$key]['opened_groups_count']++;
        }

        if ($row['type'] === EventType::group_contact->name) {
            $this->aggregated[$key]['contacted_groups_count']++;
        }
    }

    public function save(): int
    {
        if (!$this->aggregated) {
            return 0;
        }

        $values = $bindings = [];
        foreach ($this->aggregated as $row) {
            $values[] = '(' . implode(',', array_fill(0, count($row), '?')) . ')';
            $bindings = array_merge($bindings, array_values($row));
        }

        $valuesStr = implode(', ', $values);

        $table = CityStatistics::query()->getTable();

        return db()->execute(<<<SQL
            insert into {$table} (city, search_count, opened_groups_count, contacted_groups_count, date)
             values {$valuesStr}
             on duplicate key update
                 search_count = {$table}.search_count + VALUES(search_count),
                 opened_groups_count = {$table}.opened_groups_count + VALUES(opened_groups_count),
                 contacted_groups_count = {$table}.contacted_groups_count + VALUES(contacted_groups_count)
        SQL, ...$bindings)->rowCount();
    }

    private function compositeKey(array $row): string
    {
        $city = $this->getCity($row);
        $date = Carbon::parse($row['created_at']);
        return "{$city}_{$date->toDateString()}";
    }
}
