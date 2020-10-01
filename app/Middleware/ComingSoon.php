<?php

namespace App\Middleware;

use Framework\Middleware\Middleware;

class ComingSoon implements Middleware
{
    public function handle()
    {
        if (config('app.coming_soon')) {
            print view('portal.coming_soon');
            exit;
        }
    }
}
