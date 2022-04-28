<?php

namespace App\QueryBuilders;

use App\Models\Institute;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

class Institutes extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;
    use SoftDeletes;

    protected static function getModelClass(): string
    {
        return Institute::class;
    }

    public function cityModel(): Relation
    {
        return $this->has(Has::one, Cities::class, 'name', 'city');
    }

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }
}
