<?php

declare(strict_types=1);

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
                'ip' => request()->clientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'page' => request()->uri,
                'method' => request()->requestMethod->value()
            ],
            $data
        );
        return $this->create([
            'type' => $type,
            'log' => json_encode($this->maskData($data)),
            'user_id' => (int) $user?->getId()
        ]);
    }

    private function maskData(array $data): array
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $data[$key] = $this->maskData($item);
            } elseif (str_contains((string) $key, 'password')) {
                $data[$key] = str_repeat('*', mb_strlen($item));
            }
        }

        return $data;
    }
}
