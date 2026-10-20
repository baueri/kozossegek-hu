<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Oauth;

use App\Models\ApiToken;
use App\Models\OauthAuthorizationCode;
use App\Models\OauthClient;
use App\Models\User;
use App\QueryBuilders\ApiTokens;
use App\QueryBuilders\OauthAuthorizationCodes;
use App\QueryBuilders\OauthClients;
use App\QueryBuilders\Users;
use App\Services\Oauth\OauthAuthorizationService;
use App\Services\Oauth\OauthRedirectUriValidator;
use App\Services\Oauth\Pkce;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class OauthAuthorizationServiceTest extends TestCase
{
    private OauthClients&MockObject $clients;
    private OauthAuthorizationCodes&MockObject $codes;
    private ApiTokens&MockObject $apiTokens;
    private Users&MockObject $users;
    private OauthAuthorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clients = $this->createMock(OauthClients::class);
        $this->codes = $this->createMock(OauthAuthorizationCodes::class);
        $this->apiTokens = $this->createMock(ApiTokens::class);
        $this->users = $this->createMock(Users::class);
        $this->service = new OauthAuthorizationService(
            $this->clients,
            $this->codes,
            $this->apiTokens,
            $this->users,
            new OauthRedirectUriValidator(),
        );
    }

    public function testValidateAuthorizeRequestRequiresHttpsRedirect(): void
    {
        $client = OauthClient::make([
            'client_id' => 'kozossegek-partner-sync',
            'name' => 'Partner',
            'client_secret_hash' => null,
            'redirect_uri_policy' => 'https_any',
        ]);
        $this->clients->method('findByClientId')->willReturn($client);

        $challenge = Pkce::challengeS256(str_repeat('v', 43));

        $ok = $this->service->validateAuthorizeRequest(
            'code',
            'kozossegek-partner-sync',
            'https://partner.example/cb',
            'state1',
            $challenge,
            'S256',
        );
        $this->assertTrue($ok['ok']);

        $bad = $this->service->validateAuthorizeRequest(
            'code',
            'kozossegek-partner-sync',
            'http://partner.example/cb',
            'state1',
            $challenge,
            'S256',
        );
        $this->assertFalse($bad['ok']);
        $this->assertSame('invalid_request', $bad['error']);
    }

    public function testExchangeFailsOnWrongPkceVerifier(): void
    {
        $client = OauthClient::make([
            'client_id' => 'kozossegek-partner-sync',
            'name' => 'Partner',
            'client_secret_hash' => null,
            'redirect_uri_policy' => 'https_any',
        ]);
        $this->clients->method('findByClientId')->willReturn($client);

        $verifier = str_repeat('a', 43);
        $code = OauthAuthorizationCode::make([
            'code_hash' => 'x',
            'client_id' => 'kozossegek-partner-sync',
            'user_id' => 1,
            'redirect_uri' => 'https://partner.example/cb',
            'code_challenge' => Pkce::challengeS256($verifier),
            'code_challenge_method' => 'S256',
            'expires_at' => date('Y-m-d H:i:s', time() + 300),
            'used_at' => null,
        ]);
        $this->codes->method('findByPlainCode')->willReturn($code);

        $result = $this->service->exchangeAuthorizationCode(
            'authorization_code',
            'plain-code',
            'https://partner.example/cb',
            'kozossegek-partner-sync',
            str_repeat('b', 43),
        );

        $this->assertArrayHasKey('error', $result);
        $this->assertSame('invalid_grant', $result['error']);
    }

    public function testExchangeSucceedsAndCreatesOauthToken(): void
    {
        $client = OauthClient::make([
            'client_id' => 'kozossegek-partner-sync',
            'name' => 'Külső alkalmazás',
            'client_secret_hash' => null,
            'redirect_uri_policy' => 'https_any',
        ]);
        $this->clients->method('findByClientId')->willReturn($client);

        $verifier = str_repeat('a', 43);
        $code = OauthAuthorizationCode::make([
            'code_hash' => 'x',
            'client_id' => 'kozossegek-partner-sync',
            'user_id' => 7,
            'redirect_uri' => 'https://partner.example/cb',
            'code_challenge' => Pkce::challengeS256($verifier),
            'code_challenge_method' => 'S256',
            'expires_at' => date('Y-m-d H:i:s', time() + 300),
            'used_at' => null,
        ]);
        $this->codes->method('findByPlainCode')->willReturn($code);
        $this->codes->expects($this->once())->method('markUsed')->with($code);

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(7);
        $user->method('isActive')->willReturn(true);

        $usersQuery = $this->createMock(Users::class);
        $usersQuery->method('notDeleted')->willReturnSelf();
        $usersQuery->method('find')->with(7)->willReturn($user);
        $this->users = $usersQuery;

        $this->service = new OauthAuthorizationService(
            $this->clients,
            $this->codes,
            $this->apiTokens,
            $this->users,
            new OauthRedirectUriValidator(),
        );

        $token = ApiToken::make(['id' => 1, 'grant_type' => ApiToken::GRANT_OAUTH]);
        $this->apiTokens->expects($this->once())
            ->method('createForUser')
            ->with(
                $user,
                'Külső alkalmazás',
                ApiToken::GRANT_OAUTH,
                'kozossegek-partner-sync',
                'https://partner.example/cb',
            )
            ->willReturn(['token' => $token, 'plain_token' => 'kzh_' . str_repeat('z', 40)]);

        $result = $this->service->exchangeAuthorizationCode(
            'authorization_code',
            'plain-code',
            'https://partner.example/cb',
            'kozossegek-partner-sync',
            $verifier,
        );

        $this->assertSame('kzh_' . str_repeat('z', 40), $result['access_token']);
        $this->assertSame('Bearer', $result['token_type']);
        $this->assertNull($result['expires_in']);
    }
}
