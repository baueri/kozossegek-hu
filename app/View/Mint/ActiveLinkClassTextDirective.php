<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

final class ActiveLinkClassTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        return preg_replace_callback(
            "/@active_link_class\\(\\s*'((?:\\\\.|[^'\\\\])*)'\\s*\\)/",
            static function (array $m): string {
                $name = stripcslashes($m[1]);
                $namePhp = var_export($name, true);

                return '<?php echo route_is(' . $namePhp . ') ? \' active\' : \'\'; ?>';
            },
            $template
        ) ?? $template;
    }
}
