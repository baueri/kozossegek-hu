<?php

namespace Framework\Database\Listeners;

use Framework\Database\Events\QueryRan;
use Framework\Database\QueryHistory;
use Framework\Event\EventListener;

class LogQueryHistory implements EventListener
{
    private QueryHistory $queryHistory;

    public function __construct(QueryHistory $queryHistory)
    {
        $this->queryHistory = $queryHistory;
    }

    /**
     * @param QueryRan $event
     */
    public function trigger($event)
    {
        $this->queryHistory->pushQuery($event->query, $event->bindings, $event->time);
    }
}
