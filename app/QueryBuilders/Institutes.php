<?php

namespace App\QueryBuilders;

use App\Models\Institute;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;

class Institutes extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;

    protected static function getModelClass(): string
    {
        return Institute::class;
    }
}
