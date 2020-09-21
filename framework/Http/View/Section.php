<?php


namespace Framework\Http\View;


use Closure;

class Section
{
    /**
     * @var mixed[]
     */
    protected static $sections = [];

    /**
     * @param $name
     * @param $content
     */
    public static function add($name, $content)
    {
        static::$sections[$name][] = $content;
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

    /**
     * @param string $name
     * @return array|mixed
     */
    public static function get(string $name)
    {
        return static::$sections[$name] ?? [];
    }


    /**
     * @param string|Closure $content
     * @return Closure|false|string
     */
    private static function parseContent($content, array $args)
    {
        if ($content instanceof Closure) {
            ob_start();
            $content($args);
            return ob_get_clean();
        }

        return $content;
    }
}