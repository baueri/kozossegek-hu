<?php

namespace Framework\Database\Events;

use Framework\Event\Event;

class QueryRan extends Event
{
    protected static array $listeners = [];

    public string $query;

    public array $bindings;

    public float $time;

    public function __construct(string $query, array $bindings, float $time)
    {
        $this->query = $query;
        $this->bindings = $bindings;
        $this->time = $time;
    }
}
