<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Framework\Console\Command;
use Framework\Console\Out;

class SiteMapGenerator implements Command
{
    public function __construct(
        private readonly SiteMapGeneratorService $generator
    ) {
    }

    public static function signature(): string
    {
        return 'sitemap:generate';
    }

    public function handle(): void
    {
        Out::info('Generating sitemap.xml ...');
        $this->generator->run();
        Out::success('Done!');
    }
}