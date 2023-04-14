<?php

namespace App\Admin\Components\DebugBar;

abstract class DebugBarTab
{
    abstract public function getTitle(): string;

    abstract public function render(): string;

    final public function generateIcon(): string
    {
        return ($icon = $this->icon()) ? sprintf('<i class="%s"></i> ', $icon) : '';
    }

    public function icon(): string
    {
        return '';
    }
}
