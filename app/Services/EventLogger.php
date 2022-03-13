<?php

namespace App\Services;

use App\Models\EventLog;
use App\Models\User;

interface EventLogger
{
    public function logEvent(string $type, array $data = [], ?User $user = null): ?EventLog;
}
