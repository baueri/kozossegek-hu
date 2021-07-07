<?php

namespace App\Http\Components;

use http\Exception\InvalidArgumentException;

class ComponentParser
{
    public function render(string $componentName, ...$args): string
    {
        $component = config("view.components.{$componentName}");

        if (!$component) {
            throw new InvalidArgumentException('component does not exists');
        }

        return app($component)->render(...$args);
    }
}