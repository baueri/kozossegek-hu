<?php

namespace Framework\Http\View;

use Closure;
use Exception;
use Framework\Http\View\Directives\Directive;
use Framework\Http\View\Directives\RegisterableDirective;
use InvalidArgumentException;

class ViewParser
{
    /**
     * @var Directive[]
     */
    protected static array $directives = [];

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
     * @param Directive|string $directive
     * @param Closure|null $callback
     */
    public static function registerDirective($directive, $callback = null)
    {
        if ($directive instanceof Directive) {
            static::$directives[get_class($directive)] = $directive;
        } elseif (is_string($directive) && is_callable($callback)) {
            static::$directives[$directive] = new RegisterableDirective($directive, $callback);
        } else {
            throw new InvalidArgumentException('invalid directive');
        }
    }
}
