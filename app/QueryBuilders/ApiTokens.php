<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\ApiToken;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Support\StringHelper;

/**
 * @extends EntityQueryBuilder<ApiToken>
 */
class ApiTokens extends EntityQueryBuilder
{
    public const TABLE = 'api_tokens';

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    public function forUser(User $user): static
    {
        return $this->where('user_id', $user->getId())->orderBy('created_at', 'desc');
    }

    public function findByPlainToken(string $plainToken): ?ApiToken
    {
        return $this->where('token_hash', hash('sha256', $plainToken))->first();
    }

    /**
     * @return array{token: ApiToken, plain_token: string}
     */
    public function createForUser(User $user, string $name): array
    {
        $plainToken = 'kzh_' . $this->generateSecret(40);

        $token = $this->create([
            'user_id' => $user->getId(),
            'name' => StringHelper::shorten(trim($name), 100) ?: 'API kulcs',
            'token_prefix' => substr($plainToken, 0, 12),
            'token_hash' => hash('sha256', $plainToken),
        ]);

        return ['token' => $token, 'plain_token' => $plainToken];
    }

    private function generateSecret(int $length): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($alphabet) - 1;
        $secret = '';

        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, $max)];
        }

        return $secret;
    }

    public function touchLastUsed(ApiToken $token): void
    {
        $this->save($token, ['last_used_at' => now()]);
    }
}
