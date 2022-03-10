<?php


namespace Framework\Http\View\Directives;


class EchoDirective implements Directive
{

    public function getPattern(): string
    {
        return '/\{\{([^\}\}]+?)\}\}/';
    }

    public function getReplacement(array $matches): string
    {
        return trim('<?php echo ' . $matches[1] . '; ?>');
    }
}
