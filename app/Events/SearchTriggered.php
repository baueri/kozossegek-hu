<?php

namespace App\Events;

/**
 * Description of SearchTriggered
 *
 * @author ivan
 */
class SearchTriggered extends BaseLogEvent
{
    protected static $listeners = [
        \App\EventListeners\LogSearch::class
    ];
    
}


