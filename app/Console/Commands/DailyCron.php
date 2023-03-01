<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use Framework\Console\BaseCommands\ClearCache;
use Framework\Console\Color;
use Framework\Console\Command;
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
        $silent = (bool) $this->getOption('silent');

        $jobs = [
            resolve(ClearUserSessionCommand::class),
            resolve(AggregateLogsCommand::class),
            resolve(SiteMapGenerator::class)->withArgs('--ping-google=' . (int) app()->envIs(Environment::production)),
            resolve(OpenStreetMapSync::class),
            resolve(ClearCache::class)
        ];

        $hasErrors = false;

        array_walk($jobs, function (Command $job) use (&$hasErrors, $silent) {
            try {
                $this->output->info('executing ' . $job::signature() . '... ', !$silent);
                $response = $job->silent($silent)->handle();
                if ($response) {
                    $this->output->writeln("error", Color::red);
                    $hasErrors = true;
                } else {
                    $this->output->writeln("done", Color::green);
                }
            } catch (Throwable $e) {
                $hasErrors = true;
                $this->output->writeln("error", Color::red);
                report($e);
            }
        });

        if (!$hasErrors) {
            $message = 'success';
            $this->output->success('DAILY CRON RAN SUCCEFFULLY');
        } else {
            $message = 'fail';
            $this->output->warning('DAILY CRON RAN WITH SOME ERRORS');
        }

        $file = ROOT . '.daily_cron_last_run';
        $date = date('Y-m-d H:i:s');
        file_put_contents($file, "{$date} - {$message}\r\n", FILE_APPEND);
    }
}
