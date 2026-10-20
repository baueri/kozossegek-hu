<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\OauthAuthorizationCode;
use Framework\Model\EntityQueryBuilder;

/**
 * @extends EntityQueryBuilder<OauthAuthorizationCode>
 */
class OauthAuthorizationCodes extends EntityQueryBuilder
{
    public const TABLE = 'oauth_authorization_codes';

    public function findByPlainCode(string $plainCode): ?OauthAuthorizationCode
    {
        return $this->where('code_hash', hash('sha256', $plainCode))->first();
    }

    public function markUsed(OauthAuthorizationCode $code): void
    {
        $this->save($code, ['used_at' => now()]);
    }
}
