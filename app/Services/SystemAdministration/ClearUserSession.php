<?php

namespace App\Services\SystemAdministration;

/**
 * Több, mint egy napos user session-ök törlése
 * @package App\Services\SystemAdministration
 */
class ClearUserSession
{
    public function run()
    {
        return (bool) db()->delete('delete from user_sessions where created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)');
    }
}
