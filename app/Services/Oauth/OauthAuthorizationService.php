<?php

declare(strict_types=1);

namespace App\Services\Oauth;

use App\Models\ApiToken;
use App\Models\OauthAuthorizationCode;
use App\Models\OauthClient;
use App\Models\User;
use App\QueryBuilders\ApiTokens;
use App\QueryBuilders\OauthAuthorizationCodes;
use App\QueryBuilders\OauthClients;
use App\QueryBuilders\Users;

final class OauthAuthorizationService
{
    private const CODE_TTL_SECONDS = 300;

    public function __construct(
        private readonly OauthClients $clients,
        private readonly OauthAuthorizationCodes $codes,
        private readonly ApiTokens $apiTokens,
        private readonly Users $users,
        private readonly OauthRedirectUriValidator $redirectUriValidator,
    ) {
    }

    public function findClient(string $clientId): ?OauthClient
    {
        return $this->clients->findByClientId($clientId);
    }

    /**
     * @return array{ok: true}|array{ok: false, error: string, description: string}
     */
    public function validateAuthorizeRequest(
        string $responseType,
        string $clientId,
        string $redirectUri,
        string $state,
        string $codeChallenge,
        string $codeChallengeMethod,
    ): array {
        if ($responseType !== 'code') {
            return $this->fail('unsupported_response_type', 'Only response_type=code is supported.');
        }

        if ($state === '') {
            return $this->fail('invalid_request', 'state is required.');
        }

        $client = $this->findClient($clientId);
        if (!$client) {
            return $this->fail('invalid_client', 'Unknown client_id.');
        }

        if (!$client->isPublic()) {
            return $this->fail('unauthorized_client', 'Only public clients are supported.');
        }

        if (!$this->redirectUriValidator->isValid($client, $redirectUri)) {
            return $this->fail('invalid_request', 'redirect_uri must be an absolute https URL.');
        }

        if (strtoupper($codeChallengeMethod) !== 'S256') {
            return $this->fail('invalid_request', 'code_challenge_method must be S256.');
        }

        if (!Pkce::isValidChallenge($codeChallenge)) {
            return $this->fail('invalid_request', 'Invalid code_challenge.');
        }

        return ['ok' => true];
    }

    public function createAuthorizationCode(
        OauthClient $client,
        User $user,
        string $redirectUri,
        string $codeChallenge,
        string $codeChallengeMethod = 'S256',
    ): string {
        $plainCode = bin2hex(random_bytes(32));

        $this->codes->create([
            'code_hash' => hash('sha256', $plainCode),
            'client_id' => $client->client_id,
            'user_id' => $user->getId(),
            'redirect_uri' => $redirectUri,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => strtoupper($codeChallengeMethod),
            'expires_at' => date('Y-m-d H:i:s', time() + self::CODE_TTL_SECONDS),
            'used_at' => null,
        ]);

        return $plainCode;
    }

    /**
     * @return array{access_token: string, token_type: string, expires_in: null}|array{error: string, error_description: string}
     */
    public function exchangeAuthorizationCode(
        string $grantType,
        string $code,
        string $redirectUri,
        string $clientId,
        string $codeVerifier,
    ): array {
        if ($grantType !== 'authorization_code') {
            return [
                'error' => 'unsupported_grant_type',
                'error_description' => 'Only grant_type=authorization_code is supported.',
            ];
        }

        $client = $this->findClient($clientId);
        if (!$client || !$client->isPublic()) {
            return [
                'error' => 'invalid_client',
                'error_description' => 'Unknown or unsupported client.',
            ];
        }

        if (!Pkce::isValidVerifier($codeVerifier)) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'Invalid code_verifier.',
            ];
        }

        $authCode = $this->codes->findByPlainCode($code);
        if (!$authCode) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'Invalid authorization code.',
            ];
        }

        if ($authCode->isUsed() || $authCode->isExpired()) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'Authorization code expired or already used.',
            ];
        }

        if ($authCode->client_id !== $client->client_id) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'Code was not issued to this client.',
            ];
        }

        if (!hash_equals($authCode->redirect_uri, $redirectUri)) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'redirect_uri mismatch.',
            ];
        }

        if (!Pkce::verifyS256($codeVerifier, $authCode->code_challenge)) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'PKCE verification failed.',
            ];
        }

        $user = $this->users->notDeleted()->find($authCode->user_id);
        if (!$user || !$user->isActive()) {
            return [
                'error' => 'invalid_grant',
                'error_description' => 'User is not available.',
            ];
        }

        $this->codes->markUsed($authCode);

        $created = $this->apiTokens->createForUser(
            $user,
            $client->name,
            ApiToken::GRANT_OAUTH,
            $client->client_id,
        );

        return [
            'access_token' => $created['plain_token'],
            'token_type' => 'Bearer',
            'expires_in' => null,
        ];
    }

    public function revokePlainToken(string $plainToken): bool
    {
        $token = $this->apiTokens->findByPlainToken($plainToken);
        if (!$token) {
            return false;
        }

        $this->apiTokens->deleteModel($token);

        return true;
    }

    /**
     * @return array{ok: false, error: string, description: string}
     */
    private function fail(string $error, string $description): array
    {
        return ['ok' => false, 'error' => $error, 'description' => $description];
    }
}
