<?php

namespace App\EventListeners;

use App\Events\BaseLogEvent;
use App\Services\EventLogger;
use Framework\Event\Event;
use Framework\Event\EventListener;

class BaseLogger implements EventListener
{
    private EventLogger $eventLogger;

    public function __construct(EventLogger $eventLogRepo)
    {
        $this->eventLogger = $eventLogRepo;
    }

    /**
     * @param BaseLogEvent|Event $event
     */
    public function trigger($event)
    {
        $this->eventLogger->logEvent($event->logType, $event->data, $event->user);
    }
}
