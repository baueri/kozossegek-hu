<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use Framework\Console\Command;
use Framework\Console\Out;
use Framework\Enums\Environment;
use Throwable;

class DailyCron extends Command
{
    public static function signature(): string
    {
        return 'cron:daily';
    }

    public function handle(): void
    {
        $jobs = [
            resolve(ClearUserSessionCommand::class),
            resolve(AggregateLogsCommand::class),
            resolve(SiteMapGenerator::class)->withArgs('--ping-google=' . (int) app()->envIs(Environment::production)),
            resolve(OpenStreetMapSync::class)
        ];

        $hasErrors = false;

        array_walk($jobs, function (Command $job) use (&$hasErrors) {
            try {
                $job->handle();
            } catch (Throwable $e) {
                $hasErrors = true;
                report($e);
            }
        });

        if (!$hasErrors) {
            Out::success('DAILY CRON RAN SUCCEFFULLY');
        } else {
            Out::warning('DAILY CRON RAN WITH SOME ERRORS');
        }
    }
}
