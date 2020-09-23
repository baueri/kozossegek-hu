<?php


namespace Framework\Middleware;


use Framework\Application;

class BaseAuthMiddleware implements Middleware
{
    /**
     * @var Application
     */
    private $app;

    /**
     * BaseAuthMiddleware constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle()
    {
        if (!$this->app->config('app.base_auth')) {
            return true;
        }

        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];

        $validated = $user == $this->app->config('app.base_auth.user') && $pass === $this->app->config('app.base_auth.password');

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="kozossegek.hu Basic Authentication"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized");
        }

        return true;
    }
}