<?php

namespace Framework\Console\BaseCommands;

use Exception;
use Framework\Console\Command;
use Framework\Console\Out;

class ClearCache implements Command
{

    public static function signature(): string
    {
        return 'cache:clear';
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        if (!rrmdir(CACHE . '/')) {
            Out::fatal('Nem sikerült a cache törlés');
        }

        Out::success('Sikeres cache törlés');
    }
}
