<?php

namespace Framework\Http\View\Directives;

class RegisterableDirective extends AtDirective
{
    private $name;

    private $replacementCallback;

   /**
    * @param string $name
    * @param Closure|string $replacementCallback
    */
    public function __construct($name, $replacementCallback)
    {
        $this->name = $name;

        $this->replacementCallback = $replacementCallback;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getReplacement(array $matches)
    {
        return call_user_func($this->replacementCallback, $matches);
    }
}
