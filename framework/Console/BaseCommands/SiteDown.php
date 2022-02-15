<?php

 namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\Out;
 use Framework\Maintenance;

class SiteDown implements Command
{
    public static function signature(): string
    {
        return 'site:down';
    }

    public function handle(): void
    {
        (new Maintenance())->down();

        Out::warning('The site is now down for maintenance');
    }
}
