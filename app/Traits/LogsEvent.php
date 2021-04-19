<?php

namespace App\Traits;

use App\Repositories\EventLogRepository;

trait LogsEvent
{
    private ?EventLogRepository $eventLogRepository = null;

    protected function getEventLogger()
    {
        if (is_null($this->eventLogRepository)) {
            $this->eventLogRepository = event_logger();
        }

        return $this->eventLogRepository;
    }
}
