<?php


namespace App\Console;

use App\Console\Commands\ClearUserSession;
use Framework\Console\ConsoleKernel as Kernel;

class ConsoleKernel extends Kernel
{
    protected $commands = [
        ClearUserSession::class
    ];
}
