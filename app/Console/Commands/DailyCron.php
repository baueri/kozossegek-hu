<?php

namespace App\Console\Commands;

use Framework\Console\Command;

class DailyCron implements Command
{

    public static function signature()
    {
        return 'cron:daily';
    }

    public function handle()
    {

    }
}
