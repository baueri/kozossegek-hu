<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReplaceGroupView extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $this->execute('CREATE OR REPLACE VIEW v_groups AS
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
            GROUP BY groups.id');
    }
}
