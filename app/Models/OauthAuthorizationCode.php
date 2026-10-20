<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\OauthAuthorizationCodes;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property string $code_hash
 * @property string $client_id
 * @property int $user_id
 * @property string $redirect_uri
 * @property string $code_challenge
 * @property string $code_challenge_method
 * @property string $expires_at
 * @property string|null $used_at
 */
class OauthAuthorizationCode extends Entity
{
    use HasTimestamps;

    protected static ?string $builder = OauthAuthorizationCodes::class;

    public function isExpired(): bool
    {
        $expiresAt = $this->getAttributes()['expires_at'] ?? null;

        return strtotime((string) $expiresAt) <= time();
    }

    public function isUsed(): bool
    {
        $usedAt = $this->getAttributes()['used_at'] ?? null;

        return $usedAt !== null && $usedAt !== '';
    }
}
