<?php


namespace Framework\Http\View\Directives;

interface Directive
{
    /**
     * @return string
     */
    public function getPattern();

    /**
     * @param array $matches
     * @return string
     */
    public function getReplacement(array $matches);
}