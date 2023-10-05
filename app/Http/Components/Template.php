<?php

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class Template extends TagComponent
{
    public function __construct(protected string $extends = 'portal2.main')
    {
    }

    public function render(): string
    {
        return view($this->extends, ['main' => $this->slot]);
    }
}