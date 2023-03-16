<?php

declare(strict_types=1);

namespace App\ThirdParty\Http\Controllers\Api\V1;

use App\Auth\Authenticate;
use App\Models\ThirdPartyCredential;
use App\QueryBuilders\ThirdPartyCredentials;
use Firebase\JWT\JWT;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Controller;
use Framework\Http\Response;
use Framework\PasswordGenerator;
use InvalidArgumentException;

class AuthController extends Controller
{
    /**
     * @throws UnauthorizedException
     */
    public function authorize(Authenticate $authenticate, ThirdPartyCredentials $credentials, PasswordGenerator $passwordGenerator): array
    {
        Response::asJson();

        $user = $authenticate->authenticate($this->request->get('user'), $this->request->get('password'));

        if (!$user) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!$this->request->get('site_url')) {
            throw new InvalidArgumentException('`site_url` is required');
        }

        $credential = $credentials->create([
            'app_name' => $this->request->get('site_url'),
            'app_secret' => $secret = $passwordGenerator->generate(32),
            'user_id' => $user->getId()
        ]);

        return ['token' => $this->getJwt($credential), 'secret' => $secret];
    }

    public function authenticate(ThirdPartyCredentials $credentials)
    {
        $credential = $credentials
            ->where('app_name', $this->request->get('app_name'))
            ->where('app_secret', $this->request->get('app_secret'))
            ->firstOrFail();

        return ['token' => $this->getJwt($credential)];
    }

    private function getJwt(ThirdPartyCredential $credential): string
    {
        $payload = [
            'iss' => $credential->app_name,
            'exp' => now()->addDay()->timestamp,
            'context' => [
                'app' => [
                    'secret' => $credential->app_secret
                ]
            ]
        ];

        return JWT::encode($payload, _env('API_SITE_KEY'), 'HS256');
    }
}
