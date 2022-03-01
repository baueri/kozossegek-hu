<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroup;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\SoftDeletes;
use Framework\Support\Collection;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\ChurchGroup>
 */
class ChurchGroups extends EntityQueryBuilder
{
    use SoftDeletes;

    protected static function getModelClass(): string
    {
        return ChurchGroup::class;
    }

    public function active(): static
    {
        return $this->where('pending', 0)
            ->where('status', 'active')
            ->notDeleted();
    }

    public static function countGroupyBy(string $column, $values): Collection
    {
        return static::query()
            ->whereIn($column, $values)
            ->active()
            ->countBy($column);
    }
}
