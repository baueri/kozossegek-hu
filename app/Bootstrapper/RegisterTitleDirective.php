<?php

namespace App\Bootstrapper;

use App\Directives\TitleDirective;
use App\Directives\LoggedInDirective;
use Framework\Bootstrapper;
use Framework\Http\View\ViewParser;

class RegisterTitleDirective implements Bootstrapper
{
    public function boot()
    {
        ViewParser::registerDirective(new TitleDirective());
        
        ViewParser::registerDirective(new class() implements \Framework\Http\View\Directives\Directive{
            public function getPattern()
            {
                return '/\@auth|\@endauth/';
            }

            public function getReplacement(array $matches)
            {
                if ($matches[0] == '@endauth') {
                    return '<?php endif; ?>';
                }

                return '<?php if(\App\Auth\Auth::loggedin()): ?>';
            }
        });
    }
}
