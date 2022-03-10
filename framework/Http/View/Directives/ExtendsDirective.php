<?php

namespace Framework\Http\View\Directives;

class ExtendsDirective implements Directive
{

    public function getReplacement(array $matches): string
    {
        $content = preg_replace('/@extends\(\s*([^\)]+?)\s*\)/', '', $matches[0]);
        $view = $matches[1];
        $sectionCommand = '<?php $__env->getSection()->set(' . $view . ', function($args) { extract($args); ?> ' . PHP_EOL . $content . PHP_EOL . '<?php }); ?>' . PHP_EOL;
        $viewCommand = '<?php echo $__env->view(' . $view . ', $args); ?>' . PHP_EOL;
        return $sectionCommand . $viewCommand;
    }

    public function getPattern(): string
    {
        return '/@extends\(\s*([^\)]+?)\s*\).*/s';
    }
}
