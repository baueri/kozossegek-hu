<?php

namespace Framework\Http\View;

use Closure;

class Section
{
    /**
     * @var string[]|Closure[]
     */
    protected static array $sections = [];

    public static function add(string $name, string|Closure $content): void
    {
        static::$sections[$name][] = $content;
    }

    public static function set(string $name, string|Closure $content): void
    {
        static::$sections[$name] = [$content];
    }

    public function yield(string $name, array $args = []): string
    {
        $closures = static::get($name);

        if (!$closures) {
            return '';
        }

        $out = '';

        foreach ($closures as $content) {
            $out .= static::parseContent($content, $args);
        }

        return $out;
    }

    public static function get(string $name): array|Closure|string
    {
        return static::$sections[$name] ?? [];
    }

    private static function parseContent($content, array $args): string
    {
        if ($content instanceof Closure) {
            ob_start();
            echo $content($args);
            return ob_get_clean();
        }

        return $content;
    }
}
