<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroupView;
use App\Models\User;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\ChurchGroupView>
 */
class GroupViews extends ChurchGroups
{
    public const TABLE = 'v_groups';

    protected static function getModelClass(): string
    {
        return ChurchGroupView::class;
    }

    public function tags(): Relation
    {
        return $this->has(Has::many, GroupTags::class, 'group_id');
    }

    public function forUser(User $user): self
    {
        return $this->where('user_id', $user->id);
    }
}
