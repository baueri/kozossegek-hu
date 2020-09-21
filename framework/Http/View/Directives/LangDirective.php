<?php


namespace Framework\Http\View\Directives;


class LangDirective implements Directive
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return '/@(lang(_f)?)\(\s*([^\)]+?)\s*\)/';
    }

    public function getReplacement(array $matches)
    {
        $method = $matches[1];

        return '<?php echo ' . $method . '( ' . $matches[3] . '); ?>';
    }
}