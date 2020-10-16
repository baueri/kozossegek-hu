<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateWidgetsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('widgets')
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('type', MysqlAdapter::PHINX_TYPE_STRING, ['length' => 20])
            ->addColumn('uniqid', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('data', MysqlAdapter::PHINX_TYPE_TEXT)
            ->addIndex('uniqid', ['unique' => true])
            ->addIndex('type')
            ->create();
    }

    public function down()
    {
        $this->table('widgets')->drop()->save();
    }
}
