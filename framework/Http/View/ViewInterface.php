<?php


namespace Framework\Http\View;


interface ViewInterface
{
    public function view($view, array $args = []);
}