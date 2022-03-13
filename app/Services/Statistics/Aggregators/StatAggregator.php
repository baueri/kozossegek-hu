<?php

namespace App\Services\Statistics\Aggregators;

abstract class StatAggregator
{
    private array $groups = [];

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
            return $this->groups[$groupId] ??= builder('v_groups')->where('id', $groupId)->first();
        }

        return null;
    }
}