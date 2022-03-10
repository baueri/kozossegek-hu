<?php

namespace App\Directives;

use Framework\Http\View\Directives\Directive;

class LoggedInDirective implements Directive
{
    public function getPattern(): string
    {
        return '/\@auth|\@endauth/';
    }

    public function getReplacement(array $matches): string
    {
        if ($matches[0] == '@endauth') {
            return '<?php endif; ?>';
        }

        return '<?php if(\App\Auth\Auth::loggedin()): ?>';
    }
}
