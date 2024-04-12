<?php

namespace Framework\Http\View\Directives;

class LangDirective implements Directive
{

    public function getPattern(): string
    {
        return '/@(lang(_f)?)\(\s*([^\)]+?)\s*\)/';
    }

    public function getReplacement(array $matches): string
    {
        $method = $matches[1];

        return '<?php echo ' . $method . '( ' . $matches[3] . '); ?>';
    }
}
