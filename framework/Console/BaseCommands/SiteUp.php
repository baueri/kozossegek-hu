<?php

declare(strict_types=1);

 namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\Out;
 use Framework\Maintenance;

class SiteUp extends Command
{

    public static function signature(): string
    {
        return 'site:up';
    }

    public static function description(): string
    {
        return 'maintenance mode KIKAPCSOLASA';
    }

    public function handle(): void
    {
        (new Maintenance())->up();

        Out::success('The site is now on line');
    }
}
