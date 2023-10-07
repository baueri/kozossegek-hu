<?php

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class Icon extends TagComponent
{
    private string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function render(): string
    {
        return <<<HTML
            <i class="fa fa-{$this->class}">{$this->slot}</i>
        HTML;
    }
}