<?php

declare(strict_types=1);

namespace Framework\Traits;

use Framework\Support\StringHelper;

trait BootsTraits
{
    public function bootTraits()
    {
        $traits = class_uses_recursive($this);

        $booted = [];

        foreach ($traits as $trait) {
            if (!in_array($trait, $booted)) {
                $name = get_class_name($trait);
                $method = "boot{$name}";
                if (method_exists($this, $method)) {
                    $this->{$method}();
                    $booted[] = $trait;
                }
            }
        }
    }
}