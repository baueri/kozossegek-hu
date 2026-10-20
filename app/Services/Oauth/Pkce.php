<?php

declare(strict_types=1);

namespace App\Services\Oauth;

final class Pkce
{
    public static function challengeS256(string $codeVerifier): string
    {
        return self::base64UrlEncode(hash('sha256', $codeVerifier, true));
    }

    public static function verifyS256(string $codeVerifier, string $codeChallenge): bool
    {
        if ($codeVerifier === '' || $codeChallenge === '') {
            return false;
        }

        return hash_equals($codeChallenge, self::challengeS256($codeVerifier));
    }

    public static function isValidChallenge(string $challenge): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_-]{43,128}$/', $challenge);
    }

    public static function isValidVerifier(string $verifier): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_-]{43,128}$/', $verifier);
    }

    private static function base64UrlEncode(string $raw): string
    {
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }
}
