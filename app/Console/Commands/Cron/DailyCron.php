<?php

declare(strict_types=1);

namespace App\Console\Commands\Cron;

use App\Console\Commands\AggregateLogsCommand;
use App\Console\Commands\ClearUserSessionCommand;
use App\Console\Commands\SiteMapGenerator;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use Framework\Console\BaseCommands\ClearCache;
use Framework\Console\BaseCommands\CronCommand;
use Framework\Enums\Environment;

class DailyCron extends CronCommand
{
    public static function signature(): string
    {
        return 'cron:daily';
    }

    protected function jobs(): array
    {
        return [
            resolve(ClearUserSessionCommand::class),
            resolve(AggregateLogsCommand::class),
            resolve(SiteMapGenerator::class)->withArgs('--ping-google=' . (int) app()->envIs(Environment::production)),
            resolve(OpenStreetMapSync::class),
            resolve(ClearCache::class)
        ];
    }
}
