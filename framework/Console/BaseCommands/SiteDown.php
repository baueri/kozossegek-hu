<?php

declare(strict_types=1);

namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\Out;

class SiteDown extends Command
{
    public static function signature(): string
    {
        return 'site:down';
    }

    public static function description(): string
    {
        return 'maintenance mode BEKAPCSOLASA';
    }

    public function handle(): void
    {
        root()->file('.maintenance')->touch();

        Out::warning('The site is now down for maintenance');
    }
}
