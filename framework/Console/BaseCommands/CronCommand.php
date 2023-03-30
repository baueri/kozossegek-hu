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
    abstract protected function jobs(): array;

    public function handle(): void
    {
        $silent = (bool) $this->getOption('silent');

        $jobs = $this->jobs();

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
