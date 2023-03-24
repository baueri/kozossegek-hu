<?php

declare(strict_types=1);

namespace App\ThirdParty\Http\Controllers\Api\V1;

use App\Auth\Authenticate;
use App\Models\ThirdPartyCredential;
use App\QueryBuilders\ThirdPartyCredentials;
use Firebase\JWT\JWT;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Controller;
use Framework\Http\Exception\HttpException;
use Framework\Http\Exception\NotFoundException;
use Framework\Http\Response;
use Framework\Http\ResponseStatus;
use InvalidArgumentException;

class AuthController extends Controller
{
    /**
     * @throws UnauthorizedException
     */
    public function authenticate(Authenticate $authenticate, ThirdPartyCredentials $credentials): array
    {
        Response::asJson();

        $user = $authenticate->authenticate($this->request->get('user'), $this->request->get('password'));

        if (!$user) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!$this->request->get('site_url')) {
            throw new InvalidArgumentException('`site_url` is required');
        }

        $credential = $credentials->getCredentials($user, $this->request->get('site_url'));

        return ['api_key' => $credential->api_key, 'token' => $this->createJWT($credential)];
    }

    /**
     * @throws HttpException|NotFoundException
     */
    public function getToken(ThirdPartyCredentials $credentials): array
    {
        $credential = $credentials
            ->where('api_key', $this->request->get('api_key'))
            ->first();

        if (!$credential) {
            abort(ResponseStatus::UNAUTHORIZED->value, 'Unauthorized');
        }

        return ['token' => $this->createJWT($credential)];
    }

    private function createJWT(ThirdPartyCredential $credential): string
    {
        $payload = [
            'iss' => $credential->app_name,
            'exp' => now()->addDay()->timestamp,
            'context' => [
                'app' => [
                    'name' => $credential->app_name,
                    'secret' => $credential->api_key
                ]
            ]
        ];

        return JWT::encode($payload, _env('API_SITE_KEY'), 'HS256');
    }
}
