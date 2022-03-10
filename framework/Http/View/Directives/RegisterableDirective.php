<?php

namespace Framework\Http\View\Directives;

class RegisterableDirective extends AtDirective
{
    private string $name;

    /**
     * @param string $name
     * @param callable|string $replacementCallback
     */
    public function __construct(string $name, $replacementCallback)
    {
        $this->name = $name;

        $this->replacementCallback = $replacementCallback;
    }

    private $replacementCallback;

    public function getName()
    {
        return $this->name;
    }

    public function getReplacement(array $matches): string
    {
        return call_user_func($this->replacementCallback, $matches);
    }
}
