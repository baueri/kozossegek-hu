<?php

namespace Framework\Http\View\Directives;

class RouteDirective extends AtDirective
{

    public function getName()
    {
        return 'route';
    }

    public function getReplacement(array $matches)
    {
        return '<?php echo route(' . $matches[1] . '); ?>';
    }
}
