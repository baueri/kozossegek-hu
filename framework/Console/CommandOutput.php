<?php

declare(strict_types=1);

namespace Framework\Console;

/**
 * @mixin Out
 * @method CommandOutput write(string $text, Color $color = Color::default)
 * @method CommandOutput writeln(string $text, Color $color = Color::default)
 */
class CommandOutput
{
    public bool $silent = false;

    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;
        return $this;
    }

    public function __call(string $name, array $arguments): static
    {
        if ($this->silent) {
            return $this;
        }

        call_user_func_array([Out::class, $name], $arguments);

        return $this;
    }
}
