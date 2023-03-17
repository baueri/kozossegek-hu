<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\ThirdPartyCredential;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

/**
 * @phpstan-extends EntityQueryBuilder<ThirdPartyCredential>
 */
class ThirdPartyCredentials extends EntityQueryBuilder
{
    public function byCredentials(string $appName, string $secret): self
    {
        return $this->where('app_name', $appName)
            ->where('app_secret', $secret);
    }

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }
}
