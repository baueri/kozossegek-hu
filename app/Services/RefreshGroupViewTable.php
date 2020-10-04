<?php

namespace App\Services;

class RefreshGroupViewTable
{
    public static function getQuery()
    {
        return 'CREATE OR REPLACE VIEW v_groups AS
            SELECT
                groups.*,
                institutes.name as institute_name,
                institutes.city,
                institutes.district,
                institutes.leader_name,
                spiritual_movements.name as spiritual_movement
            FROM groups
            LEFT JOIN institutes ON groups.institute_id=institutes.id
            LEFT JOIN spiritual_movements ON groups.spiritual_movement_id=spiritual_movements.id
            GROUP BY groups.id';
    }
}
