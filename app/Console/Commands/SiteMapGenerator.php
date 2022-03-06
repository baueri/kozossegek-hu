<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Framework\Console\Command;
use Framework\Console\In;
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

        Out::info('sitemap successfully generated.');

        if ((new In())->confirm('Ping google?')) {
            $url = get_site_url() . '/sitemap.xml';
            file_get_contents("https://www.google.com/ping?sitemap={$url}");
        }

        Out::success('Done!');
    }
}