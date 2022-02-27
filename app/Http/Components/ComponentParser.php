<?php

namespace App\Http\Components;

use InvalidArgumentException;

class ComponentParser
{
    public function render(string $componentName, $args = [])
    {
        $component = config("view.components.{$componentName}");

        if (!$component) {
            throw new InvalidArgumentException("component `{$componentName}` does not exists");
        }

        return app()->make($component, $args);
    }
}