<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

final class LangTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        $pattern = "/@lang\\(\\s*'((?:\\\\.|[^'\\\\])*)'\\s*(?:,\\s*'((?:\\\\.|[^'\\\\])*)'\\s*)?\\)/";

        return preg_replace_callback(
            $pattern,
            static function (array $m): string {
                $key = stripcslashes($m[1]);
                $keyPhp = var_export($key, true);
                if (isset($m[2]) && $m[2] !== '') {
                    $lang = stripcslashes($m[2]);
                    $langPhp = var_export($lang, true);

                    return '<?php echo htmlspecialchars(lang(' . $keyPhp . ', ' . $langPhp . '), ENT_QUOTES, \'UTF-8\'); ?>';
                }

                return '<?php echo htmlspecialchars(lang(' . $keyPhp . '), ENT_QUOTES, \'UTF-8\'); ?>';
            },
            $template
        ) ?? $template;
    }
}
