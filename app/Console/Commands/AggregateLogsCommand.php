<?php

namespace App\Console\Commands;

use App\Services\Statistics\EventLogAggregator;
use Framework\Console\Command;

class AggregateLogsCommand implements Command
{
    public function __construct(
        private readonly EventLogAggregator $aggregator
    ) {
    }

    public static function signature(): string
    {
        return 'log:aggregate';
    }

    public function handle(): void
    {
        $this->aggregator->handle();
    }
}