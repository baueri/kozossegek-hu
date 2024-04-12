<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Bootstrapper;
use Framework\Database\Events\QueryRan;
use Framework\Database\Listeners\LogQueryHistory;

class BootListeners implements Bootstrapper
{
    public function boot(): void
    {
        QueryRan::listen(LogQueryHistory::class);
    }
}
