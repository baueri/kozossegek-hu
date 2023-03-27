<?php

declare(strict_types=1);

namespace App\Console\Commands\Cron;

use App\Console\Commands\GroupActivityConfirmNotifier;
use Framework\Console\BaseCommands\CronCommand;

class MonthlyCron extends CronCommand
{
    public static function signature(): string
    {
        return 'cron:monthly';
    }

    protected function jobs(): array
    {
        return [
            resolve(GroupActivityConfirmNotifier::class)
        ];
    }
}
