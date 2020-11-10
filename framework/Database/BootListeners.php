<?php


namespace Framework\Database;


use Framework\Bootstrapper;
use Framework\Database\Events\QueryRan;
use Framework\Database\Listeners\LogQueryHistory;
use Framework\Http\View\ViewLoaded;

class BootListeners implements Bootstrapper
{

    public function boot()
    {
        QueryRan::listen(LogQueryHistory::class);
    }
}