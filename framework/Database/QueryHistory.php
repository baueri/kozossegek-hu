<?php

namespace Framework\Database;

use Framework\Support\Collection;

class QueryHistory
{
    public readonly Collection $queryHistory;

    public function __construct()
    {
        $this->queryHistory = new Collection();
    }

    public function pushQuery($query, $bindings, $time)
    {
        $this->queryHistory->push([$query, $bindings, $time]);
    }

    public function getQueryHistory(): Collection
    {
        return $this->queryHistory;
    }

    public function getLastQuery(): array
    {
        return $this->queryHistory->last() ?: [];
    }

    public function getExecutionTime()
    {
        return $this->queryHistory->reduce(fn ($time, $row) => $time += $row[2], 0);
    }
}
