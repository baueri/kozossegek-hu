<?php


namespace Framework\Http;


use Framework\Http\View\ViewInterface;

class Controller
{
    /**
     * @var ViewInterface
     */
    protected $view;

    /**
     * Controller constructor.
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     * @param $view
     * @param array $args
     * @return string
     */
    protected function view($view, $args = [])
    {
        return $this->view->view($view, $args);
    }
}