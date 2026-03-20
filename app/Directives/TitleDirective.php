<?php

declare(strict_types=1);

namespace App\Directives;

use Framework\Http\View\Directives\AtDirective;

class TitleDirective extends AtDirective
{
    public function getName(): string
    {
        return 'title';
    }

    public function getReplacement(array $matches): string
    {
        return '<?php $__env->getSection()->add("title", ' . $matches[1] . ') ?>';
    }
}
