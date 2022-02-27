<?php

namespace App\Console\Commands;

use App\Services\Statistics\EventLogAggregator;
use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;

class DailyCron implements Command
{
    public function __construct(
        private readonly ClearUserSession $clearUserSession,
        private readonly EventLogAggregator $eventLogAggregator
    ) {
    }

    public static function signature(): string
    {
        return 'cron:daily';
    }

    public function handle(): void
    {
        $this->clearUserSession->run();
        $this->eventLogAggregator->run();
    }
}
