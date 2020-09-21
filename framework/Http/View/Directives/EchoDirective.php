<?php


namespace Framework\Http\View\Directives;


class EchoDirective implements Directive
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return '/\{\{([^\}\}]+?)\}\}/';
    }

    public function getReplacement(array $matches)
    {
        return trim('<?php echo ' . $matches[1] . '; ?>');
    }
}