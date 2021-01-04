<?php

namespace App\Middleware;

use App\EventListeners\LoadViewToDebugBar;
use Framework\Http\View\ViewLoaded;
use Framework\Middleware\Middleware;

class ListenViewLoading implements Middleware
{

    public function handle()
    {
        ViewLoaded::listen(LoadViewToDebugBar::class);
    }
}