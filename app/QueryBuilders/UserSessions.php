<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\UserSession>
 */
class UserSessions extends EntityQueryBuilder
{
    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }
}