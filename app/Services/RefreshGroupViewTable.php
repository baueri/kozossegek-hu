<?php

namespace App\Services;

class RefreshGroupViewTable
{
    /**
     * @deprecated SHOULD NOT BE USED!
     */
    public static function getQuery(): string
    {
        return 'CREATE OR REPLACE VIEW v_groups AS
            SELECT
                church_groups.*,
                institutes.name as institute_name,
                institutes.city,
                institutes.district,
                institutes.leader_name,
                spiritual_movements.name as spiritual_movement
            FROM church_groups
            LEFT JOIN institutes ON church_groups.institute_id=institutes.id
            LEFT JOIN spiritual_movements ON church_groups.spiritual_movement_id=spiritual_movements.id
            GROUP BY church_groups.id';
    }
}
