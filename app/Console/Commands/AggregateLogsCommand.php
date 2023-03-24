<?php

namespace App\Console\Commands;

use App\Services\Statistics\EventLogAggregator;
use Framework\Console\Command;

class AggregateLogsCommand extends Command
{
    public function __construct(
        private readonly EventLogAggregator $aggregator
    ) {
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'log:aggregate';
    }

    public function handle(): void
    {
        $this->output->info('aggregating event logs..');
        if (!$this->aggregator->run()) {
            $this->output->warning('no new logs aggregated.');
            return;
        }
        $this->output->success('done.');
    }
}