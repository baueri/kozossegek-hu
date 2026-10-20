<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\OauthClient;
use Framework\Model\EntityQueryBuilder;

/**
 * @extends EntityQueryBuilder<OauthClient>
 */
class OauthClients extends EntityQueryBuilder
{
    public const TABLE = 'oauth_clients';

    public function findByClientId(string $clientId): ?OauthClient
    {
        return $this->where('client_id', $clientId)->first();
    }
}
