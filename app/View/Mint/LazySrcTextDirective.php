<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Directive\Text\TextDirectiveInterface;

final class LazySrcTextDirective implements TextDirectiveInterface
{
    public function compile(string $template): string
    {
        $result = preg_replace_callback(
            '/@lazySrc\s*\(\s*([^)]*)\s*\)/',
            static function (array $m): string {
                $inner = trim($m[1]);
                $img = $inner !== '' ? $inner : '"/images/placeholder.webp"';

                return "src={$img}";
            },
            $template
        );

        return $result ?? $template;
    }
}
