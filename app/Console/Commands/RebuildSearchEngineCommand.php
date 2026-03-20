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
        parent::__construct();
    }

    public function handle(): void
    {
        $this->output->writeln('rebuilding search engine rows...');
        $this->service->run();
        $this->output->success('done.');
    }

    public static function signature(): string
    {
        return 'search-engine:rebuild';
    }

    public static function description(): string
    {
        return 'db keresohoz segedtabla ujraepitese';
    }
}
