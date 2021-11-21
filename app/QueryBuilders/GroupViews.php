<?php

namespace App\QueryBuilders;

use App\Models\EntityGroupView;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\EntityGroupView>
 */
class GroupViews extends EntityQueryBuilder
{
    public const TABLE = 'v_groups';

    protected static function getModelClass(): string
    {
        return EntityGroupView::class;
    }

    public function forUser(User $user): self
    {
        return $this->where('user_id', $user->id);
    }
}
