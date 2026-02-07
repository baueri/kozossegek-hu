<?php

declare(strict_types=1);

namespace App\Services\Captcha;

use Framework\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LogicException;

class CloudflareValidator implements CaptchaValidator
{
    private const string API_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(
        private readonly Client $client,
        private readonly string $siteKey,
        private readonly string $secret
    ) {
        if (! $this->siteKey || ! $this->secret) {
            throw new LogicException('Cloudflare Turnstile credentials are missing.');
        }
    }

    /**
     * @throws GuzzleException|Exception
     */
    public function validate(
        string $token,
        ?string $remoteIp = null
    ): void {
        $data = [
            'secret' => $this->secret,
            'response' => $token,
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        $response = $this->client->post(self::API_URL, [
            'form_params' => $data,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $response = Collection::fromJson((string) $response->getBody());

        if (! $response->get('success')) {
            throw new Exception('Turnstile validation failed: ' . implode(', ', $response->get('error-codes')));
        }
    }
}