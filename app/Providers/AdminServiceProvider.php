<?php

namespace App\Providers;

use App\Auth\Auth;
use App\Repositories\GroupViews;
use Framework\Middleware\Middleware;

class AdminServiceProvider implements Middleware
{
    public function handle(): void
    {
        if (!is_admin()) {
            return;
        }

        //TODO query-k előre build-elése, ha nincsenek belépve
    }
}
