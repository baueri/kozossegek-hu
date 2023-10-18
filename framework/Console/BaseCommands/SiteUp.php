<?php

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

    public function handle(): void
    {
        (new Maintenance())->up();

        Out::success(lang('The site is now online'));
    }
}
