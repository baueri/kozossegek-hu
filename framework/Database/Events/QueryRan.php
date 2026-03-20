<?php

declare(strict_types=1);

namespace Framework\Database\Events;

use Framework\Event\Event;

class QueryRan extends Event
{
    protected static array $listeners = [];

    public function __construct(
        public readonly string $query,
        public readonly array $bindings,
        public readonly float $time
    ) {
    }
}
