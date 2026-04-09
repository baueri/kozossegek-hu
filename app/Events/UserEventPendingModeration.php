<?php

declare(strict_types=1);

namespace App\Events;

use App\EventListeners\NotifyAdminsAboutPendingUserEvent;
use App\Models\Event as EventModel;
use Framework\Event\Event;

class UserEventPendingModeration extends Event
{
    public function __construct(
        public readonly EventModel $event
    ) {
    }

    protected static array $listeners = [
        NotifyAdminsAboutPendingUserEvent::class,
    ];
}
