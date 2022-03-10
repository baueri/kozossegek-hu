<?php

namespace Framework\Http\View\Directives;

abstract class AtDirective implements Directive
{
    abstract public function getName();

    public function getPattern(): string
    {
        return '/@' . $this->getName() . '\(?((?:[^\)]+?)\)?)\)|@end' . $this->getName() . '/';
    }
}
