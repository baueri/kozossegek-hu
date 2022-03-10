<?php


namespace Framework\Http\View\Directives;

interface Directive
{
    public function getPattern(): string;

    public function getReplacement(array $matches): string;
}