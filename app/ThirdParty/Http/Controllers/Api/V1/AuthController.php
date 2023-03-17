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
use Framework\Http\ResponseStatus;
use Framework\PasswordGenerator;
use InvalidArgumentException;

class AuthController extends Controller
{
    /**
     * @throws UnauthorizedException
     */
    public function authenticate(Authenticate $authenticate, ThirdPartyCredentials $credentials, PasswordGenerator $passwordGenerator): array
    {
        Response::asJson();

        $user = $authenticate->authenticate($this->request->get('user'), $this->request->get('password'));

        if (!$user) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!$this->request->get('site_url')) {
            throw new InvalidArgumentException('`site_url` is required');
        }

        $credential = $credentials->updateOrCreate([
            'app_name' => $this->request->get('site_url'),
            'user_id' => $user->getId()
        ],[
            'app_secret' => $secret = $passwordGenerator->generate(32),
        ]);

        return ['token' => $this->getJwt($credential), 'secret' => $secret];
    }

    public function authorize(ThirdPartyCredentials $credentials)
    {
        $credential = $credentials
            ->where('app_name', $this->request->get('app_name'))
            ->where('app_secret', $this->request->get('app_secret'))
            ->first();

        if (!$credential) {
            abort(ResponseStatus::UNAUTHORIZED->value, 'unauthorized');
        }

        return ['token' => $this->getJwt($credential)];
    }

    private function getJwt(ThirdPartyCredential $credential): string
    {
        $payload = [
            'iss' => $credential->app_name,
            'exp' => now()->addDay()->timestamp,
            'context' => [
                'app' => [
                    'name' => $credential->app_name,
                    'secret' => $credential->app_secret
                ]
            ]
        ];

        return JWT::encode($payload, _env('API_SITE_KEY'), 'HS256');
    }
}
