<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;

class DailyCron implements Command
{
    public function __construct(
        private ClearUserSession $clearUserSession
    ) {
    }

    public static function signature(): string
    {
        return 'cron:daily';
    }

    public function handle(): void
    {
        $this->clearUserSession->run();
    }
}
