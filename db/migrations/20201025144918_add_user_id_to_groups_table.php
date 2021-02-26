<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddUserIdToGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
                ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
                ->addIndex('user_id')
                ->save();

        $this->execute(\App\Services\RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('groups')
                ->removeColumn('user_id')
                ->save();
    }
}
