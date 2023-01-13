<?php

namespace App\Console\Commands;

use App\Services\RebuildSearchEngine;
use Framework\Console\Command;
use Framework\Console\Out;

class RebuildSearchEngineCommand extends Command
{
    public function __construct(
        private readonly RebuildSearchEngine $service
    ) {
    }

    public function handle(): void
    {
        Out::writeln('rebuilding search engine rows...');
        $this->service->run();
        Out::success('done.');
    }

    public static function signature(): string
    {
        return 'search-engine:rebuild';
    }
}
