<?php

namespace App\Middleware;

use Exception;
use Framework\Http\Route\RouterInterface;
use Framework\Middleware\Middleware;

class RefererMiddleware implements Middleware
{
    private RouterInterface $router;

    /**
     * @throws Exception
     */
    public function __construct(
        protected readonly string $referer
    ) {
        $this->router = app()->get(RouterInterface::class);

        if (!$this->referer) {
            throw new Exception('no referer provided. maybe you forgot?');
        }
    }

    public function handle(): void
    {
        if (preg_match('/route\(([^\)]+)\)$/', $this->referer, $matches)) {
            $referer = trim(preg_replace('@^(' . preg_quote(get_site_url()) . ')@', '', request()->referer()), '/');
            foreach ($this->router->getRoutes() as $route) {
                if ($route->matches($referer)) {
                    return;
                }
            }
        }

        if (get_site_url() . '/' . $this->referer === request()->referer()) {
            return;
        }

        log_event('referer_fail', ['request' => request()->all()]);
        http_response_code(403);
        exit(1);

    }

}