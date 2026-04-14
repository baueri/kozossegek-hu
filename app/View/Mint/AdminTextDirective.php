<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

/**
 * @admin / @admin() / @endadmin — same semantics as config/view.php admin directive.
 */
final class AdminTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        $template = str_replace('@endadmin', '<?php endif; ?>', $template);

        return preg_replace(
            '/@admin(?:\s*\(\s*\))?(?![a-zA-Z0-9_])/',
            '<?php if (\App\Auth\Auth::user()?->isAdmin()): ?>',
            $template
        ) ?? $template;
    }
}
