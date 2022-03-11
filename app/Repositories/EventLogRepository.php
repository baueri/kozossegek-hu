<?php

namespace App\Repositories;

use App\Models\EventLog;
use App\Models\User;
use App\Services\EventLogger;
use Framework\Repository;

/**
 * @phpstan-extends Repository<\App\Models\EventLog>
 */
class EventLogRepository extends Repository implements EventLogger
{
    public function logEvent(string $type, array $data = [], ?User $user = null): EventLog
    {
        return $this->create([
            'type' => $type,
            'log' => json_encode($data),
            'user_id' => (int) $user?->getId()
        ]);
    }

    public static function getModelClass(): string
    {
        return EventLog::class;
    }

    public static function getTable(): string
    {
        return 'event_logs';
    }
}
