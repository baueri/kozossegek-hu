<?php


namespace App\Console;

use App\Console\Commands\ClearUserSession;
use App\Console\Commands\PublishApp;
use App\Console\Commands\RebuildSearchEngineCommand;
use Framework\Console\ConsoleKernel as Kernel;

class ConsoleKernel extends Kernel
{
    protected array $commands = [
        ClearUserSession::class,
        RebuildSearchEngineCommand::class,
        PublishApp::class
    ];
}
