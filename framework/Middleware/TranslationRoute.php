<?php


namespace Framework\Middleware;


use Framework\Http\Request;
use Framework\Http\Route\RouterInterface;

class TranslationRoute implements Middleware
{
    /**
     * @var RouterInterface
     */
    private $request;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * TranslationRoute constructor.
     * @param Request $request
     * @param RouterInterface $router
     */
    public function __construct(Request $request, RouterInterface $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    public function handle()
    {
        $this->router->addGlobalArg('lang', getLang());
    }
}