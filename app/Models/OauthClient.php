<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\OauthClients;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property string $client_id
 * @property string $name
 * @property string|null $client_secret_hash
 * @property string $redirect_uri_policy
 */
class OauthClient extends Entity
{
    use HasTimestamps;

    protected static ?string $builder = OauthClients::class;

    public function isPublic(): bool
    {
        $hash = $this->getAttributes()['client_secret_hash'] ?? null;

        return $hash === null || $hash === '';
    }
}
