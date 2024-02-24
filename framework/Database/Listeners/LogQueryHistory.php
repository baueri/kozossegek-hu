<?php

namespace Framework\Database\Listeners;

use Framework\Database\Events\QueryRan;
use Framework\Database\QueryLog;
use Framework\Event\EventListener;

class LogQueryHistory implements EventListener
{
    public function __construct(
        protected readonly QueryLog $queryHistory
    ) {
    }

    /**
     * @param QueryRan $event
     */
    public function trigger($event): void
    {
        $this->queryHistory->pushQuery($event->query, $event->bindings, $event->time);
    }
}
