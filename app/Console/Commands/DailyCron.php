<?php

namespace App\Console\Commands;

use App\Services\Statistics\EventLogAggregator;
use App\Services\SystemAdministration\ClearUserSession;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Exception;
use Framework\Console\Command;
use Framework\Console\Out;

class DailyCron implements Command
{
    public static function signature(): string
    {
        return 'cron:daily';
    }

    public function handle(): void
    {
        try {
            resolve(ClearUserSession::class)->run();
            resolve(EventLogAggregator::class)->run();
            resolve(SiteMapGeneratorService::class)->run();
            resolve(OpenStreetMapSync::class)->handle();

            Out::success('OK');
        } catch (Exception $e) {
            report($e);
            throw $e;
        }
    }
}
