<?php

namespace Framework\Http\View\Directives;

class IncludeDirective implements Directive
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return '/@include\(\s*([^\)]+?)\s*\)/';
    }

    public function getReplacement(array $matches)
    {
        $arguments = $matches[1];
        preg_match('/[\'\"], (.*)/', $arguments);
        return '<?php echo $__env->view( ' . $matches[1] . ', $args); ?>';
    }
}
