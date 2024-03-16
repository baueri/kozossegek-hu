<?php

declare(strict_types=1);

namespace Framework\Traits;

trait BootsClass
{
    public function bootClass(): void
    {
        $bootClass = 'boot' . get_class_name($this);
        if (method_exists($this, $bootClass)) {
            $this->{$bootClass}();
        }

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