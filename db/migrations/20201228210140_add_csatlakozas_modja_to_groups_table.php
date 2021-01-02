<?php

declare(strict_types=1);

use App\Enums\JoinMode;
use App\Migration\AppMigration;
use App\Services\RefreshGroupViewTable;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddCsatlakozasModjaToGroupsTable extends AppMigration
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
        $this->table('groups')
            ->addColumn('join_mode', MysqlAdapter::PHINX_TYPE_ENUM, ['null' => true, 'values' => JoinMode::all()])
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
