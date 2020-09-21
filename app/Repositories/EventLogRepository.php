<?php

namespace App\Repositories;

/**
 * Description of EventLogRepository
 *
 * @author ivan
 */
class EventLogRepository extends \Framework\Repository
{
    //put your code here
    public static function getModelClass(): string {
        return \App\Models\EventLog::class;
    }

    public static function getTable(): string {
        return 'event_logs';
    }

}
