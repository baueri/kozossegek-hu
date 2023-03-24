<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroupView;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Support\Arr;
use Framework\Support\StringHelper;

/**
 * @phpstan-extends EntityQueryBuilder<ChurchGroupView>
 */
class GroupViews extends ChurchGroups
{
    public const TABLE = 'v_groups';

    public static function getModelClass(): string
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

    public function search(string $keyword): static
    {
        $keyword = StringHelper::sanitize(str_replace(['-', '.', '(', ')'], ' ', $keyword));
        $keywords = '+' . str_replace(' ', '* +', trim($keyword, ' ')) . '*';

        $found = db()->select('select group_id
                from search_engine 
                where MATCH(keywords) AGAINST (? IN BOOLEAN MODE)', [$keywords]);
        if ($found) {
            $this->whereIn(
                'id',
                Arr::pluck($found, 'group_id')
            );
        } else {
            $this->where('name', 'like', "%{$keyword}%");
        }

        return $this;
    }
}
