<?php

namespace Framework\Http\View\Directives;

class ForeachDirective extends AtDirective
{
    public function getName()
    {
        return 'foreach';
    }

    public function getReplacement(array $matches): string
    {
        if($matches[0] == '@endforeach') {
            return '<?php endforeach; ?>';
        }
        
        return '<?php foreach(' . $matches[1] . '): ?>';
    }

}
