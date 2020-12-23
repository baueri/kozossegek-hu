<?php

namespace App\Bootstrapper;

use App\Directives\TitleDirective;
use Framework\Bootstrapper;
use Framework\Http\View\Directives\Directive;
use Framework\Http\View\ViewParser;

class RegisterTitleDirective implements Bootstrapper
{
    public function boot()
    {
        ViewParser::registerDirective(new TitleDirective());
        foreach (config('view.directives') as $name => $callback) {
            ViewParser::registerDirective($name, $callback);
        }

        ViewParser::registerDirective(new class () implements Directive{
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
