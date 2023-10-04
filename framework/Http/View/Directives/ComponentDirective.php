<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

use Framework\Http\View\TagComponent;
use Framework\Support\StringHelper;

class ComponentDirective implements Directive
{
    public function getPattern(): string
    {
        // sample inputs
//        <com:fieldset legend="Közösség adatai">
//            <com:horizontal-input type="text" label="Neved" required="1" name="user_name"/>
//            <com:horizontal-input type="email" label="Email címed" required="1" name="email"/>
//            <com:horizontal-input type="tel" label="Telefonszám" name="phone_number" info="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is"/>
//            <com:horizontal-input type="password" label="Jelszó" required="1" name="password"/>
//            <com:horizontal-input type="password" label="Jelszó még egyszer" required="1" name="password_again"/>
//        </com:fieldset>
     // allow self-closing tags too
        return '/<com:([a-zA-Z\-\_]+)([^>]*)(?:\/?)>(?:(.*?)<\/com:\1>)?/s';
        return '/<com:([a-zA-Z\-\_]+)([^>]*)\/>|<com:([a-zA-Z\-\_]+)([^>]*)>(.*?)<\/com:\3>/s';
        return '/<com:(?<component>\w+)(?<attributes>(?:\s+\w+="[^"]*")*)\s*(?<!\/)>(?<slot>.*?)<\/com:\1\s*>/s';

    }

    public function getReplacement(array $matches): string
    {
        [, $component, $attributes, $slot] = $matches + ['', '', '', ''];

        /** @var class-string<TagComponent> $componentClassName */
        $componentClassName = '\\App\\Http\\Components\\' . StringHelper::pascalCase($component);
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

        return app()->make($componentClassName, $parsedAttributes)->withSlot($slot)->render();
//        $attributes = str_replace('=', ':', $attributes);
//        $attributes = preg_replace(['/"([^"]+)"/'], ['"$1",'], $attributes);
//        dd($attributes);
//        return <<<PHP
/*            <?php echo (new $componentClassName($attributes))->withSlot('$slot')->render(); ?>            */
//        PHP;
    }
}