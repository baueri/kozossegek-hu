<?php

declare(strict_types=1);

namespace App\Events;

use App\EventListeners\NotifyAdminsAboutGroupCreation;
use App\Models\ChurchGroup;
use Framework\Event\Event;

class ChurchGroupRegistered extends Event
{
    public function __construct(
        public readonly ChurchGroup $churchGroup
    ) {
    }

    protected static array $listeners = [
        NotifyAdminsAboutGroupCreation::class
    ];
}