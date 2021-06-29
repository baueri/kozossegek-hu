<?php

namespace Framework\Http\View;

interface ViewInterface
{
    public function view(string $view, array $args = []): string;

    public function getSection(): Section;
}
