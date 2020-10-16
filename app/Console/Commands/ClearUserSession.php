<?php

namespace App\Console\Commands;

use Framework\Console\Command;
use Framework\Console\Out;

class ClearUserSession implements Command
{
    public static function signature()
    {
        return 'clear:session';
    }

    public function handle()
    {
        $ok = db()->execute('delete from user_sessions where created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)');

        if ($ok) {
            Out::success('user session cleared');
        } else {
            Out::danger('user session clear failed');
        }

    }
}
