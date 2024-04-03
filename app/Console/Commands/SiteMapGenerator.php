<?php

declare(strict_types=1);

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
        return 'Sitemap generálás';
    }

    public function handle(): void
    {
        $this->output->info('Generating sitemap.xml ...');

        $this->generator->run();

        $this->output->info('sitemap successfully generated.');
    }
}
