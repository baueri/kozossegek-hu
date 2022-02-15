<?php

namespace App\Console\Commands;

use App\Services\RebuildSearchEngine;
use Framework\Console\Command;
use Framework\Console\Out;

class RebuildSearchEngineCommand implements Command
{
    private RebuildSearchEngine $service;

    public function __construct(RebuildSearchEngine $service)
    {
        $this->service = $service;
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
