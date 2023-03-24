<?php

declare(strict_types=1);

namespace Framework\Console;

/**
 * @mixin Out
 */
class CommandOutput
{
    protected bool $silent = false;

    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;
        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        if ($this->silent) {
            return;
        }

        call_user_func_array([Out::class, $name], $arguments);
    }
}
