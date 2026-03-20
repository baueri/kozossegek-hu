<?php

declare(strict_types=1);

use App\Migration\AppMigration;
use App\Services\RefreshGroupViewTable;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddCsatlakozasModjaToGroupsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('groups')
            ->addColumn('join_mode', MysqlAdapter::PHINX_TYPE_ENUM, ['null' => true, 'values' => ['egyeni', 'folyamatos', 'idoszakos']])
            ->save();
        $this->execute(RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('groups')
            ->removeColumn('join_mode')
            ->save();
    }
}
