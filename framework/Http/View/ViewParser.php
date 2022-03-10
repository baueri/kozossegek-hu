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
     * @throws Exception
     */
    public function parse(string $content): string
    {
        foreach (static::$directives as $directive) {
            $content = preg_replace_callback($directive->getPattern(), [$directive, 'getReplacement'], $content);

            if ($content === null) {
                throw new Exception('view pattern error');
            }
        }

        return $content;
    }

    public static function registerDirective(string|Directive $directive, ?Closure $callback = null)
    {
        if ($directive instanceof Directive) {
            static::$directives[get_class($directive)] = $directive;
        } elseif (is_callable($callback)) {
            static::$directives[$directive] = new RegisterableDirective($directive, $callback);
        } else {
            throw new InvalidArgumentException('invalid directive');
        }
    }
}
