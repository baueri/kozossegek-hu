<?php

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class Section extends TagComponent
{
    public function __construct(private readonly string $name)
    {
    }

    public function render(): string
    {
        \Framework\Http\View\Section::add($this->name, $this->slot);
        return '';
    }
}