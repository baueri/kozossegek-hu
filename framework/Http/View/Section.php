<?php

namespace Framework\Http\View;

use Closure;

class Section
{
    /**
     * @var mixed[]
     */
    protected static array $sections = [];

    /**
     * @param $name
     * @param $content
     */
    public static function add($name, $content)
    {
        static::$sections[$name][] = $content;
    }

    public static function set($name, $content)
    {
        static::$sections[$name] = [$content];
    }

    /**
     * @param string $name
     * @param array $args
     * @return string
     */
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

    public static function get(string $name)
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
