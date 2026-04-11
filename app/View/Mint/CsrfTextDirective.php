<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

final class CsrfTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        return str_replace(
            '@csrf',
            '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">',
            $template
        );
    }
}
