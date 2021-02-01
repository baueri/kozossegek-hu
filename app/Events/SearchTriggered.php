<?php

namespace App\Events;

use App\EventListeners\LogSearch;

/**
 * Description of SearchTriggered
 *
 * @author ivan
 */
class SearchTriggered extends BaseLogEvent
{
    protected static $listeners = [
        LogSearch::class
    ];
}


