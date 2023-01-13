<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Framework\Console\Command;
use Framework\Console\Out;

class SiteMapGenerator extends Command
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

        Out::info('sitemap successfully generated.');

        if (!$this->getOption('ping-google')) {
            Out::warning('Skipped google ping.');
            Out::success('Done');
            return;
        }

        $url = get_site_url() . '/sitemap.xml';
        file_get_contents("https://www.google.com/ping?sitemap={$url}");
        Out::success('Done!');
    }
}