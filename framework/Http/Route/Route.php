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

   /**
    * @param array|string $args
    * @return string
    */
    public function getWithArgs($args = [])
    {
        $uri = $this->uriMask;

        if (is_array($args)) {
            foreach ($args as $key => $arg) {
                if (strpos($uri, '{' . $key . '}') !== false) {
                    $uri = str_replace('{' . $key . '}', $arg, $uri);
                    unset($args[$key]);
                }
            }
        } elseif(is_string($args)) {
            $uri = preg_replace('/({\??[a-zA-Z\-\_]+})/', $args, $uri, 1);
            $args = '';
        }

        $uri = '/' . trim(preg_replace('/({\?[a-zA-Z\-\_]+})/', '', $uri), '/');

        if (!empty($args)) {
            $uri .= '?' . http_build_query($args);
        }

        return $uri;
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
            '/({[a-zA-Z\-\_\.]+})/',
            '/({\?[a-zA-Z\-\_\.]+})/',
            '/\//'
        ], [
            '([a-zA-Z0-9\-\_\.]+)',
            '([\?a-zA-Z0-9\-\_\.]+)',
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
