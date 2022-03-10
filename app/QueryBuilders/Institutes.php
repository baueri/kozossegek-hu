<?php

namespace App\QueryBuilders;

use App\Models\Institute;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\SoftDeletes;

class Institutes extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;
    use SoftDeletes;

    protected static function getModelClass(): string
    {
        return Institute::class;
    }
}
