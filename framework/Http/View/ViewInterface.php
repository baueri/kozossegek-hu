<?php


namespace Framework\Http\View;


interface ViewInterface
{
    /**
     * @param string $view
     * @param array $args
     * @return string
     */
    public function view(string $view, array $args = []);

    /**
     * @return Section
     */
    public function getSection();
}
