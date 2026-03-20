<?php

declare(strict_types=1);

namespace App\QueryBuilders;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

class GroupComments extends EntityQueryBuilder
{
    public function lastCommenter(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'last_comment_user');
    }
}