<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateGroupTagsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('group_tags')
                ->addColumn('group_id', MysqlAdapter::PHINX_TYPE_INTEGER)
                ->addColumn('tag', MysqlAdapter::PHINX_TYPE_STRING)
                ->addIndex('tag')
                ->addIndex(['group_id', 'tag'], ['unique' => true])
                ->create();
    }
    
    public function down()
    {
        $this->table('group_tags')->drop()->save();
    }
}
