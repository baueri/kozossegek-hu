<?php

namespace App\Events;

use App\Auth\AuthUser;
use Framework\Event\Event;

class BaseLogEvent extends Event
{
    public function __construct(
        public string $logType,
        public array $data = [],
        public ?AuthUser $user = null
    ) {
    }
}
