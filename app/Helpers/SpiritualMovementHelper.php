<?php

namespace App\Helpers;

use Framework\Model\ModelCollection;

class SpiritualMovementHelper
{
    public static function loadGroupsCount(ModelCollection $movements)
    {
        if ($movements->isEmpty()) {
            return [];
        }
        $ids = $movements->pluck('id')->implode(',');
        $counts = db()->select(
            "select count(*) as cnt, spiritual_movement_id
                    from church_groups
                    where spiritual_movement_id in ($ids) and deleted_at is null
                    group by spiritual_movement_id"
        );

        return $movements->withCount($counts, 'group_count', 'id', 'spiritual_movement_id');
    }
}