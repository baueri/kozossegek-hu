<?php

namespace App\Services\Statistics\Aggregators;

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