<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateSearchEngineTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('search_engine')
            ->addColumn('group_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('keywords', MysqlAdapter::PHINX_TYPE_TEXT)
            ->addIndex('group_id', ['unique' => true])
            ->addIndex('keywords', ['type' => 'fulltext'])
            ->save();
    }
    
    public function down()
    {
        $this->table('search_engine')->drop()->save();
    }
}
