<?php

namespace App\EventListeners;

/**
 * Description of BaseLogger
 *
 * @author ivan
 */
class BaseLogger implements \Framework\Event\EventListener {

    /**
     * @var \App\Repositories\EventLogRepository
     */
    private $eventLogRepo;

    public function __construct(\App\Repositories\EventLogRepository $eventLogRepo) {
        $this->eventLogRepo = $eventLogRepo;
    }
    
    public function trigger($event) {
        
        $this->eventLogRepo->create([
            'type' => $event->logType,
            'data' => json_encode($event->data),
            'user_id' => $event->user ? $event->user->id : 0,
        ]);
    }

}
