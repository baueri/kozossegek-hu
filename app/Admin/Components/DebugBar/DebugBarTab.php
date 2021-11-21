<?php

namespace App\Admin\Components\DebugBar;

abstract class DebugBarTab
{
    abstract public function getName(): string;

    abstract public function render(): string;
}
