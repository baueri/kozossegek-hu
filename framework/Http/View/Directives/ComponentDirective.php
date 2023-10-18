<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

use Framework\Http\View\TagComponent;
use Framework\Support\StringHelper;

class ComponentDirective implements Directive
{
    public function getPattern(): string
    {
        return '/<com:([a-zA-Z\-\_\.]+)([^\/?>]*)(?:\/?)>(?:(.*?)<\/com:\1>)?/s';
    }

    public function getReplacement(array $matches): string
    {
        dd('todo : dinamically load components');
        $slot = '';
        if (isset($matches[3])) {
            $slot = preg_replace_callback($this->getPattern(), [$this, 'getReplacement'], $matches[3]);
        }

        [, $component, , $attributes] = $matches + ['', '', '', ''];
        /** @var class-string<TagComponent> $componentClassName */
        if (str_contains($component, '.')) {
            $componentClassName = trim('\\' . str_replace('.', '\\', $component), '\\');
        } else {
            $componentClassName = '\\App\\Http\\Components\\' . StringHelper::pascalCase($component);
        }

        $parsedAttributes = [];

        $tag = preg_replace_callback(
            '/="([^"]*<[^"]*)"/',
            function(array $matches) {
                return '="'.htmlspecialchars($matches[1]).'"';
            },
            $matches[0]
        );

        foreach (@simplexml_load_string($tag)->attributes() as $key => $attribute) {
            if (StringHelper::startsWith($key, ':')) {
                $attribute = "<?php echo $attribute; ?>";
            }
            $parsedAttributes[ltrim($key, ':')] = (string) $attribute;
        }
        return app()->get($componentClassName, $parsedAttributes)->withSlot($slot)->render();
//        $attributes = str_replace('=', ':', $attributes);
//        $attributes = preg_replace(['/"([^"]+)"/'], ['"$1",'], $attributes);
//        return <<<PHP
/*            <?php echo (new $componentClassName($attributes))->withSlot('$slot')->render(); ?>            */
//        PHP;
    }
}