<?php

namespace Framework\Http\View\Directives;

class IncludeDirective implements Directive
{
    public function getPattern(): string
    {
        return '/@include\(\s*([^\)]+?)\s*\)/';
    }

    public function getReplacement(array $matches): string
    {
        $arguments = $matches[1];
        preg_match('/[\'\"], (.*)/', $arguments);
        return '<?php echo $__env->view( ' . $matches[1] . ', $args); ?>';
    }
}
