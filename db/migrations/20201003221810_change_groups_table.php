<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class ChangeGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
            ->removeColumn('spiritual_movement')
            ->removeColumn('city')
            ->addColumn('spiritual_movement_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true])
            ->addIndex('spiritual_movement_id')
            ->save();
    }

    public function down()
    {

    }
}
