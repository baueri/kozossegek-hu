<?php

namespace App\Console\Commands;

use App\Mailable\ThrowableCriticalErrorEmail;
use App\Services\Statistics\EventLogAggregator;
use App\Services\SystemAdministration\ClearUserSession;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use App\Services\SystemAdministration\SiteMap\SiteMapGenerator as SiteMapGeneratorService;
use Framework\Console\Command;
use Framework\Mail\Mailer;
use Throwable;

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
            resolve(OpenStreetMapSync::class)->run();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
