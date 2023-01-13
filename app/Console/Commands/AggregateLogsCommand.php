<?php

namespace App\Console\Commands;

use App\Services\Statistics\EventLogAggregator;
use Framework\Console\Command;
use Framework\Console\Out;

class AggregateLogsCommand extends Command
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
        Out::info('aggregating event logs..');
        if (!$this->aggregator->run()) {
            Out::warning('no new logs aggregated.');
            return;
        }
        Out::success('done.');
    }
}