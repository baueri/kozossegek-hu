<?php

namespace App\QueryBuilders;

use App\Models\Institute;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\Institute>
 */
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

    public function churchGroups(): Relation
    {
        return $this->has(Has::many, ChurchGroups::class);
    }
}
