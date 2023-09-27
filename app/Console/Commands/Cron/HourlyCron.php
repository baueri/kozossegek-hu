<?php

namespace App\Console\Commands\Cron;

use App\Console\Commands\ClearThrottle;
use Framework\Console\BaseCommands\CronCommand;

class HourlyCron extends CronCommand
{
    public static function signature(): string
    {
        return 'cron:hourly';
    }

    public function jobs(): array
    {
        return [
            new ClearThrottle()
        ];
    }
}