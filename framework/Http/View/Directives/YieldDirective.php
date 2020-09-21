<?php


namespace Framework\Http\View\Directives;


class YieldDirective extends AtDirective
{

    public function getName()
    {
        return 'yield';
    }

    /**
     * @param array $matches
     * @return string
     */
    public function getReplacement(array $matches)
    {
        return '<?php echo $__env->getSection()->yield(' . $matches[1] . ', $args); ?>';
    }
}