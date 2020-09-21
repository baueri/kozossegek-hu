<?php


namespace Framework\Http;


use Framework\Http\View\Exception\ViewNotFoundException;
use Framework\Http\View\View;

class Controller
{
    /**
     * @var View
     */
    protected $view;

    /**
     * Controller constructor.
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param $view
     * @param array $args
     * @return string
     * @throws ViewNotFoundException
     */
    protected function view($view, $args = [])
    {
        return $this->view->view($view, $args);
    }
}