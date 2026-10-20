<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\ApiToken;
use Tests\TestCase;

final class ApiTokenConnectedHostTest extends TestCase
{
    public function testPrefersRedirectUriHost(): void
    {
        $token = ApiToken::make([
            'redirect_uri' => 'https://templom.example/wp-admin/oauth/callback',
            'webhook_url' => 'https://hooks.other.example/groups',
        ]);

        $this->assertSame('templom.example', $token->connectedHost());
    }

    public function testFallsBackToWebhookHost(): void
    {
        $token = ApiToken::make([
            'redirect_uri' => null,
            'webhook_url' => 'https://partner.example/hooks/groups',
        ]);

        $this->assertSame('partner.example', $token->connectedHost());
    }

    public function testReturnsNullWhenNoUrl(): void
    {
        $token = ApiToken::make([
            'redirect_uri' => null,
            'webhook_url' => null,
        ]);

        $this->assertNull($token->connectedHost());
    }
}
