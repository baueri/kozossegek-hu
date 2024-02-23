<?php

namespace App\Repositories;

use App\Models\EventLog;
use App\Models\User;
use App\Services\EventLogger;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends EntityQueryBuilder<EventLog>
 */
class EventLogs extends EntityQueryBuilder implements EventLogger
{
    public function logEvent(string $type, array $data = [], ?User $user = null): EventLog
    {
        $data = array_merge(
            [
                'referer' => $_SERVER['HTTP_REFERER'] ?? '',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'page' => request()->uri,
                'method' => request()->requestMethod->value()
            ],
            $data
        );
        return $this->create([
            'type' => $type,
            'log' => json_encode($data),
            'user_id' => (int) $user?->getId()
        ]);
    }
}
