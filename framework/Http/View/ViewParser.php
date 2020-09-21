<?php


namespace Framework\Http\View;


use Exception;
use Framework\Http\View\Directives\Directive;

class ViewParser
{
    /**
     * @var Directive[]
     */
    protected static $directives = [];

    /**
     * @param string $content
     * @return string|null
     * @throws Exception
     */
    public static function parse(string $content)
    {
        foreach (static::$directives as $directive) {

            $content = preg_replace_callback($directive->getPattern(), [$directive, 'getReplacement'], $content);

            if ($content === null) {

                throw new Exception('view pattern error');

            }

        }

        return $content;
    }

    /**
     * @param Directive $directive instance or class name of the directive
     */
    public static function registerDirective(Directive $directive)
    {
        static::$directives[get_class($directive)] = $directive;
    }
}