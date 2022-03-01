<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroupView;
use App\Models\UserLegacy;

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

    public function forUser(UserLegacy $user): self
    {
        return $this->where('user_id', $user->id);
    }
}
