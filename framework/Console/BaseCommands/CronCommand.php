<?php

declare(strict_types=1);

namespace Framework\Console\BaseCommands;

use Framework\Console\Color;
use Framework\Console\Command;
use Throwable;

abstract class CronCommand extends Command
{
    /**
     * @phpstan-return Command[]
     */
    abstract public function jobs(): array;

    public function handle(): void
    {
        $silent = $this->getOption('silent');

        $jobs = $this->jobs();

        $hasErrors = false;

        array_walk($jobs, function (Command $job) use (&$hasErrors, $silent) {
            try {
                $this->output->info('executing ' . $job::signature() . '... ', !$silent);
                if (!is_null($silent)) {
                    $job->silent((bool) $silent);
                }
                $response = $job->handle();
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

        $date = date('Y-m-d H:i:s');
        root()->save('.daily_cron_last_run', "{$date} - {$message}\r\n", FILE_APPEND);
    }
}
