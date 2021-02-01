<?php

namespace App\Traits;

use App\Repositories\EventLogRepository;

trait LogsEvent
{
    private ?EventLogRepository $eventLogRepository;

    protected function getEventLogger()
    {
        if (is_null($this->eventLogRepository)) {
            $this->eventLogRepository = app()->get(EventLogRepository::class);
        }

        return $this->eventLogRepository;
    }
}
