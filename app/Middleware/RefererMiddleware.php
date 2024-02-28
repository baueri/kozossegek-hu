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
        $referer = trim(preg_replace('@^(' . preg_quote(get_site_url()) . ')@', '', request()->referer()), '/');
        if (preg_match('/route\(([^\)]+)\)$/', $this->referer, $matches)) {
            $expectedReferer = router()->find($matches[1]);
            if ($expectedReferer->matches($referer)) {
                return;
            }
        }

        if (str_starts_with($this->referer, get_site_url()) && $this->referer === request()->referer()) {
            return;
        }

        if (get_site_url() . '/' . $this->referer === request()->referer()) {
            return;
        }

        log_event('referer_fail', ['request' => request()->all()]);
        abort(403);
    }

}
