<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Framework\Console\Command;

class SiteMapGenerator extends Command
{
    public function __construct(
        private readonly SiteMapGeneratorService $generator
    ) {
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'sitemap:generate';
    }

    public static function description(): string
    {
        return 'Sitemap generálás. A `--ping-google=1` kapcsolóval a generálás után megpingeli a google-t, hogy mentse le náluk az új sitemap-et.';
    }

    public function handle(): void
    {
        $this->output->info('Generating sitemap.xml ...');

        $this->generator->run();

        $this->output->info('sitemap successfully generated.');

        if (!$this->getOption('ping-google')) {
            $this->output->warning('Skipped google ping.');
            $this->output->success('Done');
            return;
        }

        $url = get_site_url() . '/sitemap.xml';
        file_get_contents("https://www.google.com/ping?sitemap={$url}");
        $this->output->success('Done!');
    }
}
