<?php

namespace App\Bootstrapper;

use App\Directives\TitleDirective;
use Framework\Bootstrapper;
use Framework\Http\View\ViewParser;

class RegisterTitleDirective implements Bootstrapper
{
    public function boot()
    {
        ViewParser::registerDirective(new TitleDirective());
    }
}