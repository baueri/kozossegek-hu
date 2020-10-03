<?php

 namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\ConsoleKernel;
 use Framework\Console\Out;
 use Framework\Maintenance;

 class SiteDown implements Command
 {
     public static function signature()
     {
         return 'site:down';
     }

     public function handle()
     {
         (new Maintenance)->down();

         Out::warning('The site is now down for maintenance');
     }
 }
