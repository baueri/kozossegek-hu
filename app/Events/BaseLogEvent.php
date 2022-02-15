<?php

namespace App\Events;

use App\Models\UserLegacy;
use Framework\Event\Event;

class BaseLogEvent extends Event
{
    public ?UserLegacy $user;

    public array $data;

    public string $logType;

    /**
     *
     * @param string $logType
     * @param array $data
     * @param UserLegacy|null $user
     */
    public function __construct(string $logType, array $data = [], ?UserLegacy $user = null)
    {
        $this->logType = $logType;
        $this->data = $data;
        $this->user = $user;
    }
}
