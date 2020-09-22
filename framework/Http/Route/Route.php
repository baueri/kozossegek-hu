<?php


namespace Framework\Http\Route;


class Route implements RouteInterface
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uriMask;

    /**
     * @var string
     */
    protected $as;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $use;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * Route constructor.
     * @param string $method
     * @param string $uriMask
     * @param string $as
     * @param string $controller
     * @param string $use
     * @param array $middleware
     * @param $view
     */
    public function __construct($method, $uriMask, $as, $controller, $use, $middleware, $view)
    {
        $this->method = $method;
        $this->uriMask = $uriMask;
        $this->as = $as;
        $this->controller = trim($controller, '\\');
        $this->use = $use;
        $this->middleware = $middleware;
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUriMask(): string
    {
        return $this->uriMask;
    }

    /**
     * @return string
     */
    public function getAs(): string
    {
        return $this->as;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getUse(): string
    {
        return $this->use;
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getWithArgs(array $args = [])
    {
        $uri = $this->uriMask;

        foreach ($args as $key => $arg) {
            $uri = str_replace('{' . $key . '}', $arg, $uri);
        }
        return '/' . trim(preg_replace('/({\?[a-zA-Z\-\_]+})/', '', $uri), '/');
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function matches(string $uri): bool
    {
        $pattern = '/^' . $this->getUriForPregReplace() . '$/';
        return $this->uriMask == $uri || preg_match_all($pattern, $uri);
    }

    /**
     * @return null|string
     */
    public function getUriForPregReplace()
    {
        return preg_replace([
            '/({[a-zA-Z\-\_]+})/',
            '/({\?[a-zA-Z\-\_]+})/',
            '/\//'
        ], [
            '([a-zA-Z0-9\-\_]+)',
            '([\?a-zA-Z0-9\-\_]+)',
            '\/'
        ], trim($this->uriMask, '/'));
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    public function requestMethodIs($method)
    {
        if (strpos($this->method, '|') !== false) {
            return strpos($this->method, $method) >= 0;
        }
        
        return $this->method == $method || $this->method == 'ALL';
    }
}