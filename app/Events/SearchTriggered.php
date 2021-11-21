<?php

namespace App\Events;

use App\EventListeners\LogSearch;

class SearchTriggered extends BaseLogEvent
{
    protected static array $listeners = [
        LogSearch::class
    ];
}


