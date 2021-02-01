<?php

namespace App\Events;

use App\Models\User;
use Framework\Event\Event;

class BaseLogEvent extends Event
{
    public ?User $user;

    public array $data;

    public string $logType;

    /**
     *
     * @param string $logType
     * @param array $data
     * @param User|null $user
     */
    public function __construct(string $logType, array $data = [], ?User $user = null)
    {
        $this->logType = $logType;
        $this->data = $data;
        $this->user = $user;
    }
}
