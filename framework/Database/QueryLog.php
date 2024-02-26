<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Support\Collection;

class QueryLog
{
    public readonly Collection $queryLog;

    public function __construct()
    {
        $this->queryLog = new Collection();
    }

    public function pushQuery($query, $bindings, $time): void
    {
        $this->queryLog->push([$query, $bindings, $time]);
    }

    public function getQueryLog(): Collection
    {
        return $this->queryLog;
    }

    public function getLastQuery(): array
    {
        return $this->queryLog->last() ?: [];
    }

    public function getExecutionTime()
    {
        return $this->queryLog->reduce(fn ($time, $row) => $time += $row[2], 0);
    }
}
