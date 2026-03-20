<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

class YieldDirective extends AtDirective
{
    public function getName(): string
    {
        return 'yield';
    }

    /**
     * @param array $matches
     * @return string
     */
    public function getReplacement(array $matches): string
    {
        return '<?php echo $__env->getSection()->yield(' . $matches[1] . ', $args); ?>';
    }
}
