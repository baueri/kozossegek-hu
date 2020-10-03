<?php

 namespace Framework\Console\BaseCommands;

 use Framework\Console\Command;
 use Framework\Console\ConsoleKernel;
 use Framework\Console\Out;
 use Framework\Maintenance;

 class SiteUp implements Command
 {

     public static function signature()
     {
         return 'site:up';
     }

     public function handle()
     {
         (new Maintenance)->up();

         Out::warning('The site is now on line');
     }
 }