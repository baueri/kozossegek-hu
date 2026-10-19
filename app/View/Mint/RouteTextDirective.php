<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

/**
 * @route('name', $args) with balanced parentheses; href-safe via htmlspecialchars.
 */
final class RouteTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        $needle = '@route(';
        $len = strlen($template);
        $out = '';
        $offset = 0;

        while (($pos = strpos($template, $needle, $offset)) !== false) {
            $out .= substr($template, $offset, $pos - $offset);
            $i = $pos + strlen($needle);
            $depth = 1;
            $argsStart = $i;

            while ($i < $len && $depth > 0) {
                $c = $template[$i];
                if ($c === '(') {
                    $depth++;
                } elseif ($c === ')') {
                    $depth--;
                    if ($depth === 0) {
                        break;
                    }
                } elseif ($c === "'" || $c === '"') {
                    $quote = $c;
                    $i++;
                    while ($i < $len) {
                        if ($template[$i] === '\\') {
                            $i += 2;

                            continue;
                        }
                        if ($template[$i] === $quote) {
                            break;
                        }
                        $i++;
                    }
                }
                $i++;
            }

            if ($depth !== 0) {
                $out .= substr($template, $pos);

                break;
            }

            $inner = substr($template, $argsStart, $i - $argsStart);
            $out .= '<?php echo htmlspecialchars(route(' . $inner . '), ENT_QUOTES, \'UTF-8\'); ?>';
            $offset = $i + 1;
        }

        $out .= substr($template, $offset);

        return $out;
    }
}
