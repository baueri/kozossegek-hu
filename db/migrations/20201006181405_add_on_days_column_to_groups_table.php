<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use App\Services\RefreshGroupViewTable;

final class AddOnDaysColumnToGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
            ->addColumn('on_days', MysqlAdapter::PHINX_TYPE_SET, ['null' => true, 'values' => ['he','ke','sze','csu','pe','szo','vas'], 'after' => 'age_group'])
            ->addIndex('on_days')
            ->save();

        $this->execute(RefreshGroupViewTable::getQuery());

    }

    public function down()
    {
        $this->table('groups')->removeColumn('on_days')->save();
    }
}
