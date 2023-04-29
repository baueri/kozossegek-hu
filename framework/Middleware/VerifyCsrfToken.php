<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Http\Exception\TokenMismatchException;
use Framework\Http\Request;
use Framework\Http\Route\Route;
use Framework\Http\Route\XmlRouter;
use Framework\Http\Session;

class VerifyCsrfToken implements Middleware
{
    protected array $except = [];

    public function __construct(
        private readonly Request $request
    ) {
        $this->except = config('app.exclude_csrf');
    }

    /**
     * @throws TokenMismatchException
     */
    public function handle(): void
    {
        if (
            $this->isReading() ||
            $this->isExcepted() ||
            $this->tokenMatches()
        ) {
            return;
        }
        throw new TokenMismatchException('csrf token mismatch');
    }

    private function isExcepted(): bool
    {
        if (in_array($this->request->uri, $this->except)) {
            return true;
        }
        foreach ($this->except as $except) {
            if ($this->request->route->getAs() === $except || preg_match("#{$this->request->route->getUriMask()}#", $except)) {
                return true;
            }
        }

        return false;
    }

    private function isReading(): bool
    {
        return in_array($this->request->requestMethod, ['GET', 'HEAD', 'OPTIONS']);
    }

    private function tokenMatches(): bool
    {
        $token = $this->request->token();
        return $token && Session::token() && $token === Session::token();
    }
}