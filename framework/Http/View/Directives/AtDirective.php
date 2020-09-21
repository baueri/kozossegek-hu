<?php


namespace Framework\Http\View\Directives;

use Closure;

abstract class AtDirective implements Directive
{

    /**
     * @var Closure
     */
    protected $callback;

    abstract public function getName();

    /**
     * @inheritDoc
     */
    public function getPattern()
    {
        return '/@' . $this->getName() . '\(\s*([^\)]+?)\s*\)|@end' . $this->getName() . '/';
    }
}