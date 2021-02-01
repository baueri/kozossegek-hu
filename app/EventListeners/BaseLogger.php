<?php

namespace App\EventListeners;

use App\Events\BaseLogEvent;
use App\Repositories\EventLogRepository;
use Framework\Event\Event;
use Framework\Event\EventListener;

/**
 * Description of BaseLogger
 *
 * @author ivan
 */
class BaseLogger implements EventListener
{

    private EventLogRepository $eventLogRepo;

    public function __construct(EventLogRepository $eventLogRepo)
    {
        $this->eventLogRepo = $eventLogRepo;
    }

    /**
     * @param BaseLogEvent|Event $event
     */
    public function trigger($event)
    {
        $this->eventLogRepo->logEvent($event->logType, $event->data, $event->user);
    }
}
