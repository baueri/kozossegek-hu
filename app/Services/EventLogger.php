<?php

namespace App\Services;

use App\Models\EventLog;
use App\Models\UserLegacy;

interface EventLogger
{
    public function logEvent(string $type, array $data = [], ?UserLegacy $user = null): ?EventLog;
}
