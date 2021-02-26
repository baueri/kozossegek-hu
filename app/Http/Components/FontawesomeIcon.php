<?php

namespace App\Http\Components;

class FontawesomeIcon
{
    public function render(string $name, string $title = '', string $additionalClass = '')
    {
        return <<<EOT
            <i class="fa fa-{$name} {$additionalClass}" title="{$title}"></i>            
        EOT;
    }
}
