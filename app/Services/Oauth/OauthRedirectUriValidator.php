<?php

declare(strict_types=1);

namespace App\Services\Oauth;

use App\Models\OauthClient;

final class OauthRedirectUriValidator
{
    public function isValid(OauthClient $client, string $redirectUri): bool
    {
        $redirectUri = trim($redirectUri);
        if ($redirectUri === '') {
            return false;
        }

        $parts = parse_url($redirectUri);
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }

        return match ($client->redirect_uri_policy) {
            'https_any' => strtolower($parts['scheme']) === 'https',
            default => false,
        };
    }
}
