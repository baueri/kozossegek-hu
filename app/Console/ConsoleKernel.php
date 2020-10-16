<?php


namespace App\Console;

use App\Console\Commands\ClearUserSession;
use Framework\Console\ConsoleKernel as Kernel;
use App\Console\Commands\RebuildSearchEngineCommand;

class ConsoleKernel extends Kernel
{
    protected $commands = [
        ClearUserSession::class,
        RebuildSearchEngineCommand::class
    ];
}
