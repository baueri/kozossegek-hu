<?php

declare(strict_types=1);

namespace Tests\Unit\QueryBuilders;

use App\QueryBuilders\ApiTokens;
use ReflectionClass;
use Tests\TestCase;

final class ApiTokensTest extends TestCase
{
    public function testFindByPlainTokenHashesBeforeLookup(): void
    {
        $plain = 'kzh_' . str_repeat('a', 40);
        $expectedHash = hash('sha256', $plain);

        $tokens = $this->getMockBuilder(ApiTokens::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->getMock();

        $tokens->expects($this->once())
            ->method('where')
            ->with('token_hash', $expectedHash)
            ->willReturnSelf();
        $tokens->expects($this->once())
            ->method('first')
            ->willReturn(null);

        $this->assertNull($tokens->findByPlainToken($plain));
    }

    public function testCreateForUserStoresHashNotPlainToken(): void
    {
        try {
            db()->first('select 1');
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available for api_tokens insert.');
        }

        // Ensure table exists from migrations; skip if not.
        try {
            db()->first('select 1 from api_tokens limit 1');
        } catch (\Throwable) {
            $this->markTestSkipped('api_tokens table is not available.');
        }

        $userId = (int) (db()->first('select id from users where deleted_at is null limit 1')['id'] ?? 0);
        if ($userId <= 0) {
            $this->markTestSkipped('No user available for token creation test.');
        }

        $user = \App\QueryBuilders\Users::query()->find($userId);
        $this->assertNotNull($user);

        $result = ApiTokens::query()->createForUser($user, 'PHPUnit key');
        $plain = $result['plain_token'];
        $token = $result['token'];

        try {
            $this->assertStringStartsWith('kzh_', $plain);
            $this->assertSame(hash('sha256', $plain), $token->token_hash);
            $this->assertNotSame($plain, $token->token_hash);
            $this->assertSame(substr($plain, 0, 12), $token->token_prefix);

            $found = ApiTokens::query()->findByPlainToken($plain);
            $this->assertNotNull($found);
            $this->assertSame((int) $token->id, (int) $found->id);

            $this->assertNull(ApiTokens::query()->findByPlainToken($plain . 'x'));
        } finally {
            db()->execute('delete from api_tokens where id = ?', $token->id);
        }
    }

    public function testGenerateSecretLengthViaReflection(): void
    {
        $tokens = $this->getMockBuilder(ApiTokens::class)
            ->disableOriginalConstructor()
            ->getMock();
        $method = (new ReflectionClass(ApiTokens::class))->getMethod('generateSecret');
        $method->setAccessible(true);

        $secret = $method->invoke($tokens, 40);

        $this->assertSame(40, strlen($secret));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]+$/', $secret);
    }
}
