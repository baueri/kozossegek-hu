<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\ApiTokens;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property int $user_id
 * @property string|null $client_id
 * @property string $grant_type
 * @property string|null $redirect_uri
 * @property string $name
 * @property string $token_prefix
 * @property string $token_hash
 * @property string|null $last_used_at
 * @property string|null $webhook_url
 * @property string|null $webhook_secret
 * @property string|null $webhook_registered_at
 */
class ApiToken extends Entity
{
    use HasTimestamps;

    public const GRANT_PAT = 'pat';
    public const GRANT_OAUTH = 'oauth';

    protected static ?string $builder = ApiTokens::class;

    public function isOauth(): bool
    {
        return $this->grant_type === self::GRANT_OAUTH;
    }

    public function hasWebhook(): bool
    {
        $url = $this->getAttributes()['webhook_url'] ?? null;

        return $url !== null && $url !== '';
    }

    /**
     * Connected site host for display (OAuth redirect, else webhook URL).
     */
    public function connectedHost(): ?string
    {
        $attrs = $this->getAttributes();
        foreach (['redirect_uri', 'webhook_url'] as $field) {
            $url = $attrs[$field] ?? null;
            if (!is_string($url) || $url === '') {
                continue;
            }
            $host = parse_url($url, PHP_URL_HOST);
            if (is_string($host) && $host !== '') {
                return $host;
            }
        }

        return null;
    }
}
