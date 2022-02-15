<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroup;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\ChurchGroup>
 */
class ChurchGroups extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return ChurchGroup::class;
    }
}
