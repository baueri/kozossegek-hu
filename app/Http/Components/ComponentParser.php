<?php

namespace App\Http\Components;

use http\Exception\InvalidArgumentException;

class ComponentParser
{
    public function render(string $componentName, $args)
    {
        $component = config("view.components.{$componentName}");

        if (!$component) {
            throw new InvalidArgumentException('component does not exists');
        }

        return app()->make($component, $args);
    }
}