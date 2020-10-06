<?php


namespace Framework\Http\View\Directives;

abstract class AtDirective implements Directive
{

    abstract public function getName();

    /**
     * @inheritDoc
     */
    public function getPattern()
    {
        return '/@' . $this->getName() . '\(\s*([^\)]+?)\s*\)|@end' . $this->getName() . '/';
    }


}
