<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;
use Framework\Console\Out;

class ClearUserSessionCommand extends Command
{
    public static function signature(): string
    {
        return 'clear:session';
    }

    public function handle(): void
    {
        (new ClearUserSession())->run();

        Out::success('user session cleared');
    }
}
