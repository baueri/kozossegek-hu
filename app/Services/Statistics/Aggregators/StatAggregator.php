<?php

namespace App\Services\Statistics\Aggregators;
use App\QueryBuilders\Cities;
use Framework\Support\Collection;

abstract class StatAggregator
{
    private static array $groups = [];

    abstract public function add(array $row): void;

    abstract public function save(): int;

    protected function getCity(array $row): string
    {
        if ($city = $row['log']['varos'] ?? null) {
            return str_replace(['*'], [''], trim(ucfirst($city)));
        }

        $search = $row['log']['search'] ?? null;

        if ($search) {
            $keywords = Collection::fromList($search, ' ')->filter()->values();
            $city = Cities::query()->whereIn('name', $keywords)->first();
            if ($city) {
                return $city->name;
            }            
        }

        return $this->getGroup($row)['city'] ?? '';
    }

    protected function getGroup(array $row): ?array
    {
        if ($groupId = $row['log']['group_id'] ?? null) {
            return static::$groups[$groupId] ??= builder('v_groups')->where('id', $groupId)->first();
        }

        return null;
    }
}