<?php

declare(strict_types=1);

use App\Services\RefreshGroupViewTable;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddPendingColumnToGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
                ->addColumn('pending', MysqlAdapter::PHINX_TYPE_INTEGER, ['length' => 1, 'default' => 1])
                ->addIndex('pending')
                ->save();

        $this->execute('update `groups` set pending=1 WHERE status = "pending"');

        $this->execute(RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('groups')->removeColumn('pending')->save();
    }
}
