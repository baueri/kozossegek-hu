<?php

namespace App\Services\Statistics\Aggregators;

use App\Enums\EventType;
use Carbon\Carbon;
use Framework\Support\Collection;

class CityStatAggregator extends StatAggregator
{
    private array $aggregated = [];

    public function add(array $row): void
    {
        if (!in_array($row['type'], [
            EventType::search->value,
            EventType::group_profile_opened->value,
            EventType::group_contact->value
        ]) || !$this->getCity($row)) {
            return;
        }

        $key = $this->compositeKey($row);
        if (!isset($this->aggregated[$key])) {
            $this->aggregated[$key] = [
                'city' => $this->getCity($row),
                'search_count' => 0,
                'opened_groups_count' => 0,
                'contacted_groups_count' => 0,
                'date' => Carbon::parse($row['created_at'])->toDateString()
            ];
        }
        if ($row['type'] === EventType::search->value) {
            $this->aggregated[$key]['search_count']++;
        }

        if ($row['type'] === EventType::group_profile_opened->value) {
            $this->aggregated[$key]['opened_groups_count']++;
        }

        if ($row['type'] === EventType::group_contact->value) {
            $this->aggregated[$key]['contacted_groups_count']++;
        }
    }

    public function save(): void
    {
        $values = $bindings = [];
        foreach ($this->aggregated as $row) {
            $values[] = '(' . implode(',', array_fill(0, count($row), '?')) . ')';
            $bindings = array_merge($bindings, array_values($row));
        }

        $valuesStr = implode(', ', $values);

        db()->execute(<<<SQL
            insert into stat_city (city, search_count, opened_groups_count, contacted_groups_count, date)
             values {$valuesStr}
             on duplicate key update
                 search_count = stat_city.search_count + VALUES(search_count),
                 opened_groups_count = stat_city.opened_groups_count + VALUES(opened_groups_count),
                 contacted_groups_count = stat_city.contacted_groups_count + VALUES(contacted_groups_count)
        SQL, ...$bindings);
    }

    private function compositeKey(array $row): string
    {
        $city = $this->getCity($row);
        $date = Carbon::parse($row['created_at']);
        return "{$city}_{$date->toDateString()}";
    }
}