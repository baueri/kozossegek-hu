<?php

namespace Framework\Console\BaseCommands;

use Exception;
use Framework\Console\Command;

class ClearCache extends Command
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
            $this->output->fatal('Nem sikerült a cache törlés');
        }

        $this->output->success('Sikeres cache törlés');
    }
}
