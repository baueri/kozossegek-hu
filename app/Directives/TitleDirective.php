<?php

namespace App\Directives;

use Framework\Http\View\Directives\AtDirective;

class TitleDirective extends AtDirective
{

    public function getName()
    {
        return 'title';
    }

    public function getReplacement(array $matches)
    {
        return '<?php $__env->getSection()->add("title", ' . $matches[1] . ') ?>';
    }
}