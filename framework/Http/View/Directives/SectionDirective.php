<?php


namespace Framework\Http\View\Directives;


class SectionDirective extends AtDirective
{

    public function getName()
    {
        return 'section';
    }

    /**
     * @param array $matches
     * @return string
     */
    public function getReplacement(array $matches)
    {
        if (strpos($matches[0], '@end') === 0) {
            return '<?php }); ?>';
        }
        if (preg_match('/[\'\"],/', $matches[1])) {
            $endPart = '); ';
        } else {
            $endPart = ', function($args) { extract($args);';
        }

        return '<?php $__env->getSection()->add(' . $matches[1] . $endPart . ' ?>';
    }
}