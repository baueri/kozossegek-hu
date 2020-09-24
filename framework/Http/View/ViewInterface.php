<?php


namespace Framework\Http\View;


interface ViewInterface
{
    /**
     * @param $view
     * @param array $args
     * @return string
     */
    public function view($view, array $args = []);

    /**
     * @return Section
     */
    public function getSection();
}