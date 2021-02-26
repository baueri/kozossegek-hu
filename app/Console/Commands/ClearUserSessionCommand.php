<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;
use Framework\Console\Out;

class ClearUserSessionCommand implements Command
{
    public static function signature()
    {
        return 'clear:session';
    }

    public function handle()
    {
        $ok = (new ClearUserSession())->run();

        if ($ok) {
            Out::success('user session cleared');
        } else {
            Out::error('user session clear failed');
        }
    }
}
