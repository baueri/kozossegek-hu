<?php


namespace App\Admin\Components\DebugBar;


abstract class DebugBarTab
{
    abstract public function getName();

    abstract public function render();
}