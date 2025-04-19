<?php

namespace App\Middleware;

use Exception;
use Framework\Http\Route\Route;
use Framework\Middleware\Before;

readonly class RefererMiddleware implements Before
{
    /**
     * @throws Exception
     */
    public function __construct(
        protected string $referer
    ) {
        if (!$this->referer) {
            throw new Exception('no referer provided. maybe you forgot?');
        }
    }

    public function before(): void
    {
        $referer = (trim(parse_url(request()->referer())['path'] ?? '', '/ '));

        if (preg_match('/route\(([^\)]+)\)$/', $this->referer, $matches)) {
            $expectedReferer = router()->getRoutes()->find(function (Route $route) use ($matches) {
                return $route->as === $matches[1];
            });

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
