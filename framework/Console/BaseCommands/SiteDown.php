<?php

 namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\ConsoleKernel;
 use Framework\Console\Out;

 class SiteDown implements Command
 {
     public static function signature()
     {
         return 'site:down';
     }

     public function handle()
     {
         app()->down();

         Out::warning('The site is now down for maintenance');
     }
 }
