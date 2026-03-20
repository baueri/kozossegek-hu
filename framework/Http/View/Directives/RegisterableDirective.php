<?php

namespace Framework\Http\View\Directives;

use Closure;

class RegisterableDirective extends AtDirective
{
    private string $name;

    private string|Closure $replacementCallback;

    public function __construct(string $name, string|Closure $replacementCallback)
    {
        $this->name = $name;

        $this->replacementCallback = $replacementCallback;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReplacement(array $matches): string
    {
        return call_user_func($this->replacementCallback, $matches);
    }
}
