<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddInstituteName2ToChurchGroupsView extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE OR REPLACE VIEW v_groups AS
            SELECT
                church_groups.id,
                church_groups.name,
                church_groups.description,
                church_groups.institute_id,
                church_groups.group_leaders,
                users.email as group_leader_email,
                church_groups.age_group,
                church_groups.on_days,
                church_groups.occasion_frequency,
                church_groups.status,
                church_groups.created_at,
                church_groups.updated_at,
                church_groups.deleted_at,
                church_groups.spiritual_movement_id,
                church_groups.user_id,
                users.name as user_name,
                church_groups.pending,
                church_groups.document,
                church_groups.join_mode,
                church_groups.denomination,
                COALESCE(NULLIF(church_groups.image_url, ""), institutes.image_url) as image_url,
                institutes.name as institute_name,
                institutes.name2 as institute_name2,
                institutes.city,
                institutes.district,
                institutes.leader_name,
                spiritual_movements.name as spiritual_movement
            FROM church_groups
            LEFT JOIN institutes ON church_groups.institute_id=institutes.id
            LEFT JOIN users ON church_groups.user_id=users.id
            LEFT JOIN spiritual_movements ON church_groups.spiritual_movement_id=spiritual_movements.id
            GROUP BY church_groups.id
        SQL);
    }
}
