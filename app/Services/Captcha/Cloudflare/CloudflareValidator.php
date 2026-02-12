<?php

declare(strict_types=1);

namespace App\Services\Captcha\Cloudflare;

use App\Services\Captcha\CaptchaValidator;
use App\Services\Captcha\Exception;
use Framework\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LogicException;

class CloudflareValidator implements CaptchaValidator
{
    private const string API_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(
        private readonly Client $client,
        private readonly Config $config
    ) {
        if (! $this->config->siteKey || ! $this->config->secret) {
            throw new LogicException('Cloudflare Turnstile credentials are missing.');
        }
    }

    /**
     * @throws GuzzleException|Exception
     */
    public function validate(
        ?string $token,
        ?string $remoteIp = null
    ): void {
        $data = [
            'secret' => $this->config->secret,
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
            log_event('captcha_fail', ['request' => request()->all(), 'error' => implode(', ', $response->get('error-codes'))]);

            throw new Exception('Turnstile validation failed: ' . implode(', ', $response->get('error-codes')));
        }
    }
}