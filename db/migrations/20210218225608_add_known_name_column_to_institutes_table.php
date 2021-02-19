<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddKnownNameColumnToInstitutesTable extends AbstractMigration
{
    public function up()
    {
        $this->table('institutes')
            ->addColumn('name2', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true, 'after' => 'name'])
            ->addColumn('website', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->update();

        $this->execute('CREATE OR REPLACE VIEW v_groups AS
            SELECT
                church_groups.*,
                institutes.image_url as institute_image,
                institutes.name as institute_name,
                institutes.name2 as institute_name2,
                institutes.city,
                institutes.district,
                institutes.leader_name,
                spiritual_movements.name as spiritual_movement
            FROM church_groups
            LEFT JOIN institutes ON church_groups.institute_id=institutes.id
            LEFT JOIN spiritual_movements ON church_groups.spiritual_movement_id=spiritual_movements.id
            GROUP BY church_groups.id');
    }

    public function down()
    {
        $this->table('institutes')
            ->removeColumn('name2')
            ->removeColumn('website')
            ->update();
    }
}
