<?php

namespace App\Repositories;

use App\Models\EventLog;
use App\Models\UserLegacy;
use App\Services\EventLogger;
use Framework\Model\Model;
use Framework\Repository;

class EventLogRepository extends Repository implements EventLogger
{
    /**
     * @param string $type
     * @param array $data
     * @param UserLegacy|null $user
     * @return EventLog|Model|null
     */
    public function logEvent(string $type, array $data = [], ?UserLegacy $user = null): ?EventLog
    {
        return $this->create([
            'type' => $type,
            'log' => json_encode($data),
            'user_id' => $user ? $user->id : 0
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
