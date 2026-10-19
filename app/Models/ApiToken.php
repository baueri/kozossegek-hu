<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\ApiTokens;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property int $user_id
 * @property string $name
 * @property string $token_prefix
 * @property string $token_hash
 * @property string|null $last_used_at
 */
class ApiToken extends Entity
{
    use HasTimestamps;

    protected static ?string $builder = ApiTokens::class;
}
