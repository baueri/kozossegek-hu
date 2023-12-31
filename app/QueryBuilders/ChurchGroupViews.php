<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\ChurchGroupView;
use App\Models\User;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

/**
 * @phpstan-extends ChurchGroups<ChurchGroupView>
 */
class ChurchGroupViews extends ChurchGroups
{
    public const TABLE = 'v_groups';

    public function tags(): Relation
    {
        return $this->has(Has::many, GroupTags::class, 'group_id');
    }

    public function institute(): Relation
    {
        return $this->has(Has::one, Institutes::class, 'id', 'institute_id');
    }

    public function forUser(User $user): self
    {
        return $this->where('user_id', $user->id);
    }
}
