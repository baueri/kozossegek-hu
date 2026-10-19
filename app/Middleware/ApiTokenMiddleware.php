<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use App\QueryBuilders\ApiTokens;
use App\QueryBuilders\Users;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Middleware\Before;

class ApiTokenMiddleware implements Before
{
    public function __construct(
        private readonly Request $request,
        private readonly ApiTokens $apiTokens,
        private readonly Users $users,
    ) {
    }

    public function before(): void
    {
        Response::asJson();

        $plainToken = $this->extractBearerToken();
        if ($plainToken === null || $plainToken === '') {
            $this->unauthorized('Missing or invalid Authorization header. Use: Bearer <api_token>');
        }

        $token = $this->apiTokens->findByPlainToken($plainToken);
        if (!$token) {
            $this->unauthorized('Invalid API token');
        }

        $user = $this->users->notDeleted()->find($token->user_id);
        if (!$user || !$user->isActive()) {
            $this->unauthorized('Invalid API token');
        }

        Auth::setUser($user);
        $this->apiTokens->touchLastUsed($token);
    }

    private function extractBearerToken(): ?string
    {
        $header = $this->authorizationHeader();
        if ($header === '') {
            return null;
        }

        if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function authorizationHeader(): string
    {
        $candidates = [
            $this->request->getHeader('AUTHORIZATION'),
            $_SERVER['HTTP_AUTHORIZATION'] ?? null,
            $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null,
        ];

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $name => $value) {
                if (strcasecmp((string) $name, 'Authorization') === 0) {
                    $candidates[] = $value;
                    break;
                }
            }
        }

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && trim($candidate) !== '') {
                return trim($candidate);
            }
        }

        return '';
    }

    private function unauthorized(string $message): never
    {
        Response::setStatusCode(401);
        header('WWW-Authenticate: Bearer');
        echo json_encode([
            'success' => false,
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
