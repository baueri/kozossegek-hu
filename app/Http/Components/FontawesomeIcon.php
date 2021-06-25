<?php

namespace App\Http\Components;

class FontawesomeIcon
{
    final public function render(string $name, string $title = '', string $additionalClass = ''): string
    {
        return <<<EOT
            <i class="fa fa-{$name} {$additionalClass}" title="{$title}"></i>            
        EOT;
    }
}
