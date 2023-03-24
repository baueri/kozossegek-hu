<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration;

/**
 * Több, mint egy napos user session-ök törlése
 */
class ClearUserSession
{
    public function run(): bool
    {
        return (bool) db()->delete('delete from user_sessions where DATE(created_at) < DATE_SUB(NOW(), INTERVAL 1 DAY)');
    }
}
